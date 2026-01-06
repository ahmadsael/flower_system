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

    <title>Withdrawal Manage</title>

    <meta name="description" content="" />

    @include('layouts.Admin.LinkHeader')

    @include('layouts.Admin.AdminStyles')
</head>

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Spinner -->
        @include('layouts.Admin.spinner')

        <!-- Menu -->
        @include('layouts.Admin.Sidebar')
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->
            @include('layouts.Admin.NavBar')
            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">

                    <!-- Withdrawal Table -->
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

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Withdrawal Requests</h5>
                            <a href="{{ route('admin.farmer.withdrawal.export', request()->all()) }}" class="btn btn-sm btn-primary">
                                <i class="bx bx-export me-1"></i> Export CSV
                            </a>
                        </div>

                        <!-- Filter Section -->
                        <div class="card-body" style="max-height: 320px; overflow-y: auto;">
                            <form method="GET" action="{{ route('admin.farmer.withdrawal.index') }}" class="mb-4">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="search" class="form-label">Search</label>
                                        <input type="text" class="form-control" id="search" name="search"
                                               placeholder="Search by farmer name, email, reference, or note" value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" name="status" id="status">
                                            <option value="">All</option>
                                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="method" class="form-label">Method</label>
                                        <select class="form-select" name="method" id="method">
                                            <option value="">All</option>
                                            <option value="bank_transfer" {{ request('method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                            <option value="cash" {{ request('method') === 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="wallet" {{ request('method') === 'wallet' ? 'selected' : '' }}>Wallet</option>
                                            <option value="paypal" {{ request('method') === 'paypal' ? 'selected' : '' }}>PayPal</option>
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
                                        <a href="{{ route('admin.farmer.withdrawal.index') }}" class="btn btn-secondary" style="height: 48px;padding-top: 12px">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive text-nowrap">
                            <table class="table">
                                <thead>
                                <tr class="text-nowrap">
                                    <th>#</th>
                                    <th>Farmer</th>
                                    <th>Email</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Reference</th>
                                    <th>Note</th>
                                    <th>Rejection Reason</th>
                                    <th>Processed By</th>
                                    <th>Processed At</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($withdrawals as $index => $withdrawal)
                                    <tr>
                                        <th scope="row">{{ ($withdrawals->currentPage() - 1) * $withdrawals->perPage() + $index + 1 }}</th>
                                        <td>{{ $withdrawal->farmer?->name ?? 'N/A' }}</td>
                                        <td>{{ $withdrawal->farmer?->email ?? 'N/A' }}</td>
                                        <td>${{ number_format($withdrawal->amount, 2) }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $withdrawal->method)) }}</td>
                                        <td>
                                            @if ($withdrawal->status === 'pending')
                                                <div class="badge bg-warning">Pending</div>
                                            @elseif ($withdrawal->status === 'approved')
                                                <div class="badge bg-success">Approved</div>
                                            @elseif ($withdrawal->status === 'rejected')
                                                <div class="badge bg-danger">Rejected</div>
                                            @elseif ($withdrawal->status === 'processing')
                                                <div class="badge bg-info">Processing</div>
                                            @else
                                                <div class="badge bg-primary">Paid</div>
                                            @endif
                                        </td>
                                        <td>{{ $withdrawal->reference ?? 'N/A' }}</td>
                                        <td>{{ $withdrawal->note ?? 'N/A' }}</td>
                                        <td>{{ $withdrawal->rejection_reason ?? '-' }}</td>
                                        <td>{{ $withdrawal->processedBy?->name ?? '-' }}</td>
                                        <td>{{ $withdrawal->processed_at ? $withdrawal->processed_at->format('Y-m-d H:i:s') : '-' }}</td>
                                        <td>{{ $withdrawal->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if ($withdrawal->status === 'pending')
                                                        <form method="POST" action="{{ route('admin.farmer.withdrawal.approve', $withdrawal->id) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to approve this withdrawal?')">
                                                                <i class="bx bx-check me-1"></i> Approve
                                                            </button>
                                                        </form>
                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $withdrawal->id }}">
                                                            <i class="bx bx-x me-1"></i> Reject
                                                        </button>
                                                    @endif
                                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#statusModal{{ $withdrawal->id }}">
                                                        <i class="bx bx-edit me-1"></i> Change Status
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Reject Modal -->
                                            <div class="modal fade" id="rejectModal{{ $withdrawal->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <form method="POST" action="{{ route('admin.farmer.withdrawal.reject', $withdrawal->id) }}">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Reject Withdrawal</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="rejection_reason{{ $withdrawal->id }}" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                                    <textarea
                                                                        name="rejection_reason"
                                                                        id="rejection_reason{{ $withdrawal->id }}"
                                                                        class="form-control"
                                                                        rows="3"
                                                                        placeholder="Enter rejection reason"
                                                                        required
                                                                    ></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">Reject</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Status Change Modal -->
                                            <div class="modal fade" id="statusModal{{ $withdrawal->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <form method="POST" action="{{ route('admin.farmer.withdrawal.updateStatus', $withdrawal->id) }}">
                                                            @csrf
                                                            @method('put')
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Change Withdrawal Status</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="status{{ $withdrawal->id }}" class="form-label">Status <span class="text-danger">*</span></label>
                                                                    <select
                                                                        name="status"
                                                                        id="status{{ $withdrawal->id }}"
                                                                        class="form-select"
                                                                        required
                                                                    >
                                                                        <option value="pending" {{ $withdrawal->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                                        <option value="approved" {{ $withdrawal->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                                                        <option value="rejected" {{ $withdrawal->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                                        <option value="processing" {{ $withdrawal->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                                                        <option value="paid" {{ $withdrawal->status === 'paid' ? 'selected' : '' }}>Paid</option>
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
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center">No withdrawal requests found</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Section -->
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    Showing {{ $withdrawals->firstItem() ?? 0 }} to {{ $withdrawals->lastItem() ?? 0 }} of {{ $withdrawals->total() }} entries
                                </div>

                                <div>
                                    @if ($withdrawals->hasPages())
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination pagination-round pagination-primary mb-0">
                                                @if ($withdrawals->onFirstPage())
                                                    <li class="page-item disabled">
                                                        <a class="page-link" href="javascript:void(0);" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $withdrawals->previousPageUrl() }}" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                @endif

                                                @foreach ($withdrawals->getUrlRange(max(1, $withdrawals->currentPage() - 2), min($withdrawals->lastPage(), $withdrawals->currentPage() + 2)) as $page => $url)
                                                    @if ($page == $withdrawals->currentPage())
                                                        <li class="page-item active">
                                                            <a class="page-link" href="javascript:void(0);">{{ $page }}</a>
                                                        </li>
                                                    @else
                                                        <li class="page-item">
                                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach

                                                @if ($withdrawals->hasMorePages())
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $withdrawals->nextPageUrl() }}" aria-label="Next">
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
                                    <form method="GET" action="{{ route('admin.farmer.withdrawal.index') }}" class="d-inline-flex align-items-center">
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

@include('layouts.Admin.LinkJS')

</body>
</html>

