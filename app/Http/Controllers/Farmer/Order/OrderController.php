<?php

namespace App\Http\Controllers\Farmer\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,paid,processing,shipped,delivered,cancelled',
            'farmer_status' => 'nullable|in:pending,accepted,rejected',
            'payment_status' => 'nullable|in:unpaid,paid,refunded',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $farmerId = Auth::guard('farmer')->id();

        $query = Order::query()
            ->with(['customer', 'orderDetails.product'])
            ->whereHas('orderDetails.product', function ($q) use ($farmerId) {
                $q->where('created_by', $farmerId);
            })
            ->distinct();

        if (!empty($validated['search'])) {
            $searchTerm = $validated['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('order_number', 'like', '%' . $searchTerm . '%')
                    ->orWhere('phone', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('customer', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('email', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['farmer_status'])) {
            $query->where('farmer_status', $validated['farmer_status']);
        }

        if (!empty($validated['payment_status'])) {
            $query->where('payment_status', $validated['payment_status']);
        }

        if (!empty($validated['from_date'])) {
            $query->whereDate('created_at', '>=', $validated['from_date']);
        }

        if (!empty($validated['to_date'])) {
            $query->whereDate('created_at', '<=', $validated['to_date']);
        }

        $perPage = $validated['per_page'] ?? 10;

        $orders = $query->orderBy('created_at', 'desc')->paginate($perPage)->appends($request->all());

        return view('Farmer.Order.index', compact('orders'));
    }

    public function show(int $id)
    {
        $farmerId = Auth::guard('farmer')->id();

        $order = Order::query()
            ->with(['customer', 'orderDetails.product', 'orderDetails.productColor'])
            ->whereHas('orderDetails.product', function ($q) use ($farmerId) {
                $q->where('created_by', $farmerId);
            })
            ->findOrFail($id);

        return view('Farmer.Order.show', compact('order'));
    }

    public function accept(Request $request, int $id)
    {
        try {
            $farmerId = Auth::guard('farmer')->id();

            $order = Order::query()
                ->whereHas('orderDetails.product', function ($q) use ($farmerId) {
                    $q->where('created_by', $farmerId);
                })
                ->findOrFail($id);

            $order->update([
                'farmer_status' => 'accepted',
                'rejection_reason' => null,
            ]);

            return redirect()->route('farmer.order.index')->with('success_message', 'Order accepted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error_message', 'Failed to accept order: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, int $id)
    {
        try {
            $validated = $request->validate([
                'rejection_reason' => 'required|string|max:1000',
            ]);

            $farmerId = Auth::guard('farmer')->id();

            $order = Order::query()
                ->whereHas('orderDetails.product', function ($q) use ($farmerId) {
                    $q->where('created_by', $farmerId);
                })
                ->findOrFail($id);

            $order->update([
                'farmer_status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
            ]);

            return redirect()->route('farmer.order.index')->with('success_message', 'Order rejected successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error_message', 'Failed to reject order: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, int $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,paid,processing,shipped,delivered,cancelled',
            ]);

            $farmerId = Auth::guard('farmer')->id();

            $order = Order::query()
                ->whereHas('orderDetails.product', function ($q) use ($farmerId) {
                    $q->where('created_by', $farmerId);
                })
                ->findOrFail($id);

            $order->update([
                'status' => $validated['status'],
            ]);

            return redirect()->route('farmer.order.index')->with('success_message', 'Order status updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error_message', 'Failed to update order status: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $validated = $request->validate([
                'search' => 'nullable|string|max:255',
                'status' => 'nullable|in:pending,paid,processing,shipped,delivered,cancelled',
                'farmer_status' => 'nullable|in:pending,accepted,rejected',
                'payment_status' => 'nullable|in:unpaid,paid,refunded',
                'from_date' => 'nullable|date',
                'to_date' => 'nullable|date|after_or_equal:from_date',
            ]);

            $farmerId = Auth::guard('farmer')->id();

            $query = Order::query()
                ->with(['customer', 'orderDetails.product'])
                ->whereHas('orderDetails.product', function ($q) use ($farmerId) {
                    $q->where('created_by', $farmerId);
                })
                ->distinct();

            if (!empty($validated['search'])) {
                $searchTerm = $validated['search'];
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('order_number', 'like', '%' . $searchTerm . '%')
                        ->orWhere('phone', 'like', '%' . $searchTerm . '%')
                        ->orWhereHas('customer', function ($q) use ($searchTerm) {
                            $q->where('name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('email', 'like', '%' . $searchTerm . '%');
                        });
                });
            }

            if (!empty($validated['status'])) {
                $query->where('status', $validated['status']);
            }

            if (!empty($validated['farmer_status'])) {
                $query->where('farmer_status', $validated['farmer_status']);
            }

            if (!empty($validated['payment_status'])) {
                $query->where('payment_status', $validated['payment_status']);
            }

            if (!empty($validated['from_date'])) {
                $query->whereDate('created_at', '>=', $validated['from_date']);
            }

            if (!empty($validated['to_date'])) {
                $query->whereDate('created_at', '<=', $validated['to_date']);
            }

            $orders = $query->orderBy('created_at', 'desc')->get();

            $filename = 'orders_export_' . date('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = static function () use ($orders) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, [
                    'ID',
                    'Order Number',
                    'Customer Name',
                    'Customer Email',
                    'Phone',
                    'Subtotal',
                    'Tax',
                    'Discount',
                    'Total',
                    'Status',
                    'Farmer Status',
                    'Payment Status',
                    'Payment Method',
                    'Created At',
                    'Updated At',
                ]);

                foreach ($orders as $order) {
                    fputcsv($handle, [
                        $order->id,
                        $order->order_number,
                        $order->customer?->name ?? 'N/A',
                        $order->customer?->email ?? 'N/A',
                        $order->phone ?? 'N/A',
                        $order->subtotal,
                        $order->tax,
                        $order->discount,
                        $order->total,
                        ucfirst($order->status),
                        ucfirst($order->farmer_status),
                        ucfirst($order->payment_status),
                        $order->payment_method ? ucfirst($order->payment_method) : 'N/A',
                        $order->created_at?->format('Y-m-d H:i:s'),
                        $order->updated_at?->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return redirect()->back()->with('error_message', 'Failed to export orders: ' . $e->getMessage());
        }
    }
}
