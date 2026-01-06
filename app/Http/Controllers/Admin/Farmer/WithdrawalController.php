<?php

namespace App\Http\Controllers\Admin\Farmer;

use App\Http\Controllers\Controller;
use App\Models\FarmerWithdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
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

        $query = FarmerWithdrawal::query()
            ->with(['farmer', 'wallet', 'processedBy']);

        if (!empty($validated['search'])) {
            $searchTerm = $validated['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('reference', 'like', '%' . $searchTerm . '%')
                    ->orWhere('note', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('farmer', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('email', 'like', '%' . $searchTerm . '%');
                    });
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

        return view('Admin.Farmer.Withdrawal.index', compact('withdrawals'));
    }

    public function approve(Request $request, int $id)
    {
        try {
            $withdrawal = FarmerWithdrawal::query()->findOrFail($id);

            if ($withdrawal->status !== 'pending') {
                return redirect()->back()->with('error_message', 'Only pending withdrawals can be approved.');
            }

            DB::beginTransaction();

            $withdrawal->update([
                'status' => 'approved',
                'processed_by_admin_id' => Auth::guard('admin')->id(),
                'processed_at' => now(),
            ]);

            $wallet = $withdrawal->wallet;
            $wallet->update([
                'pending_withdrawal_amount' => $wallet->pending_withdrawal_amount - $withdrawal->amount,
                'last_transaction_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.farmer.withdrawal.index')->with('success_message', 'Withdrawal approved successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error_message', 'Failed to approve withdrawal: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, int $id)
    {
        try {
            $validated = $request->validate([
                'rejection_reason' => 'required|string|max:1000',
            ]);

            $withdrawal = FarmerWithdrawal::query()->findOrFail($id);

            if ($withdrawal->status !== 'pending') {
                return redirect()->back()->with('error_message', 'Only pending withdrawals can be rejected.');
            }

            DB::beginTransaction();

            $withdrawal->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'processed_by_admin_id' => Auth::guard('admin')->id(),
                'processed_at' => now(),
            ]);

            $wallet = $withdrawal->wallet;
            $wallet->update([
                'available_amount' => $wallet->available_amount + $withdrawal->amount,
                'pending_withdrawal_amount' => $wallet->pending_withdrawal_amount - $withdrawal->amount,
                'last_transaction_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.farmer.withdrawal.index')->with('success_message', 'Withdrawal rejected successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error_message', 'Failed to reject withdrawal: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, int $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,approved,rejected,processing,paid',
            ]);

            $withdrawal = FarmerWithdrawal::query()->findOrFail($id);

            $oldStatus = $withdrawal->status;
            $newStatus = $validated['status'];

            DB::beginTransaction();

            $updateData = [
                'status' => $newStatus,
                'processed_by_admin_id' => Auth::guard('admin')->id(),
                'processed_at' => now(),
            ];

            if ($newStatus === 'rejected' && empty($withdrawal->rejection_reason)) {
                $updateData['rejection_reason'] = 'Status changed to rejected';
            }

            $withdrawal->update($updateData);

            $wallet = $withdrawal->wallet;

            if ($oldStatus === 'pending' && $newStatus === 'rejected') {
                $wallet->update([
                    'available_amount' => $wallet->available_amount + $withdrawal->amount,
                    'pending_withdrawal_amount' => $wallet->pending_withdrawal_amount - $withdrawal->amount,
                    'last_transaction_at' => now(),
                ]);
            } elseif ($oldStatus === 'pending' && $newStatus === 'approved') {
                $wallet->update([
                    'pending_withdrawal_amount' => $wallet->pending_withdrawal_amount - $withdrawal->amount,
                    'last_transaction_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.farmer.withdrawal.index')->with('success_message', 'Withdrawal status updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error_message', 'Failed to update withdrawal status: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $validated = $request->validate([
                'search' => 'nullable|string|max:255',
                'status' => 'nullable|in:pending,approved,rejected,processing,paid',
                'method' => 'nullable|in:bank_transfer,cash,wallet,paypal',
                'from_date' => 'nullable|date',
                'to_date' => 'nullable|date|after_or_equal:from_date',
            ]);

            $query = FarmerWithdrawal::query()
                ->with(['farmer', 'wallet', 'processedBy']);

            if (!empty($validated['search'])) {
                $searchTerm = $validated['search'];
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('reference', 'like', '%' . $searchTerm . '%')
                        ->orWhere('note', 'like', '%' . $searchTerm . '%')
                        ->orWhereHas('farmer', function ($q) use ($searchTerm) {
                            $q->where('name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('email', 'like', '%' . $searchTerm . '%');
                        });
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

            $filename = 'withdrawals_export_' . date('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = static function () use ($withdrawals) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, [
                    'ID',
                    'Farmer Name',
                    'Farmer Email',
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
                        $withdrawal->farmer?->name ?? 'N/A',
                        $withdrawal->farmer?->email ?? 'N/A',
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
        } catch (\Exception $e) {
            return redirect()->back()->with('error_message', 'Failed to export withdrawals: ' . $e->getMessage());
        }
    }
}
