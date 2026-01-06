<?php

namespace App\Http\Controllers\Farmer\Wallet;

use App\Http\Controllers\Controller;
use App\Models\FarmerWallet;
use App\Models\FarmerWithdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,approved,rejected,processing,paid',
            'method' => 'nullable|in:bank_transfer,cash,wallet,paypal',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $farmerId = Auth::guard('farmer')->id();

        $wallet = FarmerWallet::query()
            ->with(['farmer', 'withdrawals.processedBy'])
            ->where('farmer_id', $farmerId)
            ->first();

        if (!$wallet) {
            $wallet = FarmerWallet::query()->create([
                'farmer_id' => $farmerId,
                'total_amount' => 0,
                'available_amount' => 0,
                'pending_withdrawal_amount' => 0,
            ]);
        }

        $query = FarmerWithdrawal::query()
            ->with(['processedBy'])
            ->where('farmer_id', $farmerId);

        if (!empty($validated['search'])) {
            $searchTerm = $validated['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('reference', 'like', '%' . $searchTerm . '%')
                    ->orWhere('note', 'like', '%' . $searchTerm . '%');
            });
        }

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['method'])) {
            $query->where('method', $validated['method']);
        }

        if (!empty($validated['from_date'])) {
            $query->whereDate('created_at', '>=', $validated['from_date']);
        }

        if (!empty($validated['to_date'])) {
            $query->whereDate('created_at', '<=', $validated['to_date']);
        }

        $perPage = $validated['per_page'] ?? 10;

        $withdrawals = $query->orderBy('created_at', 'desc')->paginate($perPage)->appends($request->all());

        return view('Farmer.Wallet.index', compact('wallet', 'withdrawals'));
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,approved,rejected,processing,paid',
            'method' => 'nullable|in:bank_transfer,cash,wallet,paypal',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $farmerId = Auth::guard('farmer')->id();

        $query = FarmerWithdrawal::query()
            ->with(['processedBy'])
            ->where('farmer_id', $farmerId);

        if (!empty($validated['search'])) {
            $searchTerm = $validated['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('reference', 'like', '%' . $searchTerm . '%')
                    ->orWhere('note', 'like', '%' . $searchTerm . '%');
            });
        }

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['method'])) {
            $query->where('method', $validated['method']);
        }

        if (!empty($validated['from_date'])) {
            $query->whereDate('created_at', '>=', $validated['from_date']);
        }

        if (!empty($validated['to_date'])) {
            $query->whereDate('created_at', '<=', $validated['to_date']);
        }

        $withdrawals = $query->orderBy('created_at', 'desc')->get();

        $filename = 'farmer_withdrawals_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = static function () use ($withdrawals) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID',
                'Amount',
                'Method',
                'Status',
                'Reference',
                'Note',
                'Rejection Reason',
                'Processed By',
                'Processed At',
                'Created At',
            ]);

            foreach ($withdrawals as $withdrawal) {
                fputcsv($handle, [
                    $withdrawal->id,
                    $withdrawal->amount,
                    ucfirst(str_replace('_', ' ', $withdrawal->method)),
                    ucfirst($withdrawal->status),
                    $withdrawal->reference ?? 'N/A',
                    $withdrawal->note ?? 'N/A',
                    $withdrawal->rejection_reason ?? 'N/A',
                    $withdrawal->processedBy?->name ?? 'N/A',
                    $withdrawal->processed_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $withdrawal->created_at?->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function requestWithdrawal(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'amount' => 'required|numeric|min:0.01',
                'method' => 'required|in:bank_transfer,cash,wallet,paypal',
                'reference' => 'nullable|string|max:255',
                'note' => 'nullable|string|max:1000',
            ]);

            $farmerId = Auth::guard('farmer')->id();

            $wallet = FarmerWallet::query()
                ->where('farmer_id', $farmerId)
                ->first();

            if (!$wallet) {
                return redirect()->back()->with('error_message', 'Wallet not found. Please contact support.');
            }

            if ($validatedData['amount'] > $wallet->available_amount) {
                return redirect()->back()->withInput()->with('error_message', 'Insufficient available balance. Available: $' . number_format($wallet->available_amount, 2));
            }

            DB::beginTransaction();

            $withdrawal = FarmerWithdrawal::query()->create([
                'farmer_id' => $farmerId,
                'farmer_wallet_id' => $wallet->id,
                'amount' => $validatedData['amount'],
                'method' => $validatedData['method'],
                'status' => 'pending',
                'reference' => $validatedData['reference'] ?? null,
                'note' => $validatedData['note'] ?? null,
            ]);

            $wallet->update([
                'available_amount' => $wallet->available_amount - $validatedData['amount'],
                'pending_withdrawal_amount' => $wallet->pending_withdrawal_amount + $validatedData['amount'],
                'last_transaction_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('farmer.wallet.index')->with('success_message', 'Withdrawal request submitted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error_message', 'Failed to submit withdrawal request: ' . $e->getMessage());
        }
    }
}
