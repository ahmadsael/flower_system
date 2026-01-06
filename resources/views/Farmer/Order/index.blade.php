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

    <title>Order Manage</title>

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

                    <!-- Order Table -->
                    <div class="card">
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

                        <h5 class="card-header">Order Table</h5>

                        <!-- Filter Section -->
                        <div class="card-body" style="max-height: 320px; overflow-y: auto;">
                            <form method="GET" action="{{ route('farmer.order.index') }}" class="mb-4">
                                <div class="row g-3">

                                    <div class="col-md-3">
                                        <label for="search" class="form-label">Search</label>
                                        <input type="text" class="form-control" id="search" name="search"
                                               placeholder="Search by order number, customer name, email, or phone" value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="status" class="form-label">Order Status</label>
                                        <select class="form-select" name="status" id="status">
                                            <option value="">All</option>
                                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="farmer_status" class="form-label">Farmer Status</label>
                                        <select class="form-select" name="farmer_status" id="farmer_status">
                                            <option value="">All</option>
                                            <option value="pending" {{ request('farmer_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="accepted" {{ request('farmer_status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                            <option value="rejected" {{ request('farmer_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="payment_status" class="form-label">Payment Status</label>
                                        <select class="form-select" name="payment_status" id="payment_status">
                                            <option value="">All</option>
                                            <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="from_date" class="form-label">From Date</label>
                                        <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="to_date" class="form-label">To Date</label>
                                        <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                                    </div>

                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                                        <a href="{{ route('farmer.order.index') }}" class="btn btn-secondary" style="height: 48px;padding-top: 12px">Reset</a>
                                    </div>
                                    <div class="row g-3">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                Export
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                                <li><a class="dropdown-item" href="{{ route('farmer.order.export', request()->all()) }}">Export to CSV</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- End Filter Section -->

                        <div class="table-responsive text-nowrap">
                            <table class="table">
                                <thead>
                                <tr class="text-nowrap">
                                    <th>#</th>
                                    <th>Order Number</th>
                                    <th>Customer</th>
                                    <th>Phone</th>
                                    <th>Total</th>
                                    <th>Order Status</th>
                                    <th>Farmer Status</th>
                                    <th>Payment Status</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($orders as $index => $order)
                                    <tr>
                                        <th scope="row">{{ ($orders->currentPage() - 1) * $orders->perPage() + $index + 1 }}</th>
                                        <td>{{ $order->order_number }}</td>
                                        <td>
                                            <div>{{ $order->customer?->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $order->customer?->email ?? '' }}</small>
                                        </td>
                                        <td>{{ $order->phone ?? 'N/A' }}</td>
                                        <td>${{ number_format($order->total, 2) }}</td>
                                        <td>
                                            @if ($order->status === 'pending')
                                                <div class="badge bg-warning">Pending</div>
                                            @elseif ($order->status === 'paid')
                                                <div class="badge bg-info">Paid</div>
                                            @elseif ($order->status === 'processing')
                                                <div class="badge bg-primary">Processing</div>
                                            @elseif ($order->status === 'shipped')
                                                <div class="badge bg-secondary">Shipped</div>
                                            @elseif ($order->status === 'delivered')
                                                <div class="badge bg-success">Delivered</div>
                                            @else
                                                <div class="badge bg-danger">Cancelled</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->farmer_status === 'pending')
                                                <div class="badge bg-warning">Pending</div>
                                            @elseif ($order->farmer_status === 'accepted')
                                                <div class="badge bg-success">Accepted</div>
                                            @else
                                                <div class="badge bg-danger">Rejected</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->payment_status === 'unpaid')
                                                <div class="badge bg-danger">Unpaid</div>
                                            @elseif ($order->payment_status === 'paid')
                                                <div class="badge bg-success">Paid</div>
                                            @else
                                                <div class="badge bg-warning">Refunded</div>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('farmer.order.show', $order->id) }}">
                                                        <i class="bx bx-show me-1"></i> View Details
                                                    </a>
                                                    @if ($order->farmer_status === 'pending')
                                                        <form method="post" action="{{ route('farmer.order.accept', $order->id) }}" class="d-inline">
                                                            @csrf
                                                            <button class="dropdown-item btn" type="submit" onclick="return confirm('Are you sure you want to accept this order?')">
                                                                <i class="bx bx-check me-1"></i> Accept
                                                            </button>
                                                        </form>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $order->id }}">
                                                            <i class="bx bx-x me-1"></i> Reject
                                                        </a>
                                                    @endif
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#statusModal{{ $order->id }}">
                                                        <i class="bx bx-edit-alt me-1"></i> Change Status
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $order->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $order->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="post" action="{{ route('farmer.order.reject', $order->id) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="rejectModalLabel{{ $order->id }}">Reject Order</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="rejection_reason{{ $order->id }}" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                            <textarea
                                                                name="rejection_reason"
                                                                id="rejection_reason{{ $order->id }}"
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
                                    <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1" aria-labelledby="statusModalLabel{{ $order->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="post" action="{{ route('farmer.order.updateStatus', $order->id) }}">
                                                    @csrf
                                                    @method('put')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="statusModalLabel{{ $order->id }}">Change Order Status</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="status{{ $order->id }}" class="form-label">Order Status <span class="text-danger">*</span></label>
                                                            <select name="status" id="status{{ $order->id }}" class="form-select" required>
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
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No orders found</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Section -->
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} entries
                                </div>

                                <div>
                                    @if ($orders->hasPages())
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination pagination-round pagination-primary mb-0">
                                                {{-- Previous Page Link --}}
                                                @if ($orders->onFirstPage())
                                                    <li class="page-item disabled">
                                                        <a class="page-link" href="javascript:void(0);" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $orders->previousPageUrl() }}" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                @endif

                                                {{-- Pagination Elements --}}
                                                @foreach ($orders->getUrlRange(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)) as $page => $url)
                                                    @if ($page == $orders->currentPage())
                                                        <li class="page-item active">
                                                            <a class="page-link" href="javascript:void(0);">{{ $page }}</a>
                                                        </li>
                                                    @else
                                                        <li class="page-item">
                                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach

                                                {{-- Next Page Link --}}
                                                @if ($orders->hasMorePages())
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $orders->nextPageUrl() }}" aria-label="Next">
                                                            <span aria-hidden="true">&raquo;</span>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="page-item disabled">
                                                        <a class="page-link" href="javascript:void(0);" aria-label="Next">
                                                            <span aria-hidden="true">&raquo;</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </nav>
                                    @endif
                                </div>

                                <div>
                                    <form method="GET" action="{{ route('farmer.order.index') }}" class="d-inline-flex align-items-center">
                                        @foreach(request()->except(['page', 'per_page']) as $key => $value)
                                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                        @endforeach
                                        <label class="me-2">Show</label>
                                        <select name="per_page" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                            @foreach([10, 25, 50, 100] as $perPage)
                                                <option value="{{ $perPage }}" {{ request('per_page', 10) == $perPage ? 'selected' : '' }}>
                                                    {{ $perPage }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label class="ms-2">entries</label>
                                    </form>
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

@include('layouts.Farmer.LinkJS')

</body>
</html>

