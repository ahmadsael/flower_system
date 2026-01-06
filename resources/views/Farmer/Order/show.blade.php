<!DOCTYPE html>

<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="../assets/"
    data-template="vertical-menu-template-free"
>
<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Order Details</title>

    <meta name="description" content="" />

    @include('layouts.Farmer.LinkHeader')

    @include('layouts.Farmer.FarmerStyles')
</head>

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Spinner -->
        @include('layouts.Farmer.spinner')
        
        <!-- Menu -->
        @include('layouts.Farmer.Sidebar')
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->
            @include('layouts.Farmer.NavBar')
            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">

                    {{-- message Section --}}
                    @if (session('success_message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success_message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error_message'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error_message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    {{-- end message Section --}}

                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Order Details - {{ $order->order_number }}</h5>
                                    <a href="{{ route('farmer.order.index') }}" class="btn btn-secondary">Back</a>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <h6>Customer Information</h6>
                                            <p class="mb-1"><strong>Name:</strong> {{ $order->customer?->name ?? 'N/A' }}</p>
                                            <p class="mb-1"><strong>Email:</strong> {{ $order->customer?->email ?? 'N/A' }}</p>
                                            <p class="mb-1"><strong>Phone:</strong> {{ $order->phone ?? 'N/A' }}</p>
                                            <p class="mb-0"><strong>Shipping Address:</strong> {{ $order->shipping_address ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Order Information</h6>
                                            <p class="mb-1"><strong>Order Number:</strong> {{ $order->order_number }}</p>
                                            <p class="mb-1"><strong>Order Status:</strong>
                                                @if ($order->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif ($order->status === 'paid')
                                                    <span class="badge bg-info">Paid</span>
                                                @elseif ($order->status === 'processing')
                                                    <span class="badge bg-primary">Processing</span>
                                                @elseif ($order->status === 'shipped')
                                                    <span class="badge bg-secondary">Shipped</span>
                                                @elseif ($order->status === 'delivered')
                                                    <span class="badge bg-success">Delivered</span>
                                                @else
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @endif
                                            </p>
                                            <p class="mb-1"><strong>Farmer Status:</strong>
                                                @if ($order->farmer_status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif ($order->farmer_status === 'accepted')
                                                    <span class="badge bg-success">Accepted</span>
                                                @else
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </p>
                                            <p class="mb-1"><strong>Payment Status:</strong>
                                                @if ($order->payment_status === 'unpaid')
                                                    <span class="badge bg-danger">Unpaid</span>
                                                @elseif ($order->payment_status === 'paid')
                                                    <span class="badge bg-success">Paid</span>
                                                @else
                                                    <span class="badge bg-warning">Refunded</span>
                                                @endif
                                            </p>
                                            <p class="mb-1"><strong>Payment Method:</strong> {{ $order->payment_method ? ucfirst($order->payment_method) : 'N/A' }}</p>
                                            <p class="mb-0"><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d H:i:s') }}</p>
                                        </div>
                                    </div>

                                    @if ($order->rejection_reason)
                                        <div class="alert alert-danger">
                                            <strong>Rejection Reason:</strong> {{ $order->rejection_reason }}
                                        </div>
                                    @endif

                                    <h6 class="mb-3">Order Items</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Color</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($order->orderDetails as $detail)
                                                <tr>
                                                    <td>
                                                        <div><strong>{{ $detail->product_name }}</strong></div>
                                                        @if ($detail->product)
                                                            <small class="text-muted">SKU: {{ $detail->product->sku ?? 'N/A' }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($detail->color_name)
                                                            <div>
                                                                <span class="badge" style="background-color: {{ $detail->color_hex ?? '#000' }}; color: white;">
                                                                    {{ $detail->color_name }}
                                                                </span>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>${{ number_format($detail->price, 2) }}</td>
                                                    <td>{{ $detail->quantity }}</td>
                                                    <td>${{ number_format($detail->total, 2) }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                                <td><strong>${{ number_format($order->subtotal, 2) }}</strong></td>
                                            </tr>
                                            @if ($order->tax > 0)
                                                <tr>
                                                    <td colspan="4" class="text-end"><strong>Tax:</strong></td>
                                                    <td><strong>${{ number_format($order->tax, 2) }}</strong></td>
                                                </tr>
                                            @endif
                                            @if ($order->discount > 0)
                                                <tr>
                                                    <td colspan="4" class="text-end"><strong>Discount:</strong></td>
                                                    <td><strong>-${{ number_format($order->discount, 2) }}</strong></td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                                <td><strong>${{ number_format($order->total, 2) }}</strong></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="mt-4">
                                        @if ($order->farmer_status === 'pending')
                                            <form method="post" action="{{ route('farmer.order.accept', $order->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to accept this order?')">
                                                    <i class="bx bx-check me-1"></i> Accept Order
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                                <i class="bx bx-x me-1"></i> Reject Order
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
                                            <i class="bx bx-edit-alt me-1"></i> Change Order Status
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / Content -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
</div>
<!-- / Layout wrapper -->

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('farmer.order.reject', $order->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea
                            name="rejection_reason"
                            id="rejection_reason"
                            class="form-control"
                            rows="4"
                            placeholder="Please provide a reason for rejecting this order..."
                            required
                        ></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Status Change Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('farmer.order.updateStatus', $order->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Change Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Order Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('layouts.Farmer.LinkJS')

</body>
</html>

