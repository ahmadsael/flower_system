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

    <title>Wallet Manage</title>

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

                    <!-- Wallet Summary -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Total Amount</h6>
                                    <h3 class="text-primary">${{ number_format($wallet->total_amount, 2) }}</h3>
                                    <small class="text-muted">Total credited to wallet</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Available Amount</h6>
                                    <h3 class="text-success">${{ number_format($wallet->available_amount, 2) }}</h3>
                                    <small class="text-muted">Can withdraw</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Pending Withdrawal</h6>
                                    <h3 class="text-warning">${{ number_format($wallet->pending_withdrawal_amount, 2) }}</h3>
                                    <small class="text-muted">Requested but not processed</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Request Withdrawal -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Request Withdrawal</h5>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="post" action="{{ route('farmer.wallet.requestWithdrawal') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="amount">Amount <span class="text-danger">*</span></label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            name="amount"
                                            id="amount"
                                            class="form-control @error('amount') is-invalid @enderror"
                                            placeholder="0.00"
                                            min="0.01"
                                            max="{{ $wallet->available_amount }}"
                                            value="{{ old('amount') }}"
                                            required
                                        />
                                        <small class="text-muted">Available: ${{ number_format($wallet->available_amount, 2) }}</small>
                                        @error('amount')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="method">Withdrawal Method <span class="text-danger">*</span></label>
                                        <select
                                            name="method"
                                            id="method"
                                            class="form-select @error('method') is-invalid @enderror"
                                            required
                                        >
                                            <option value="" selected disabled>Select Method</option>
                                            <option value="bank_transfer" {{ old('method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                            <option value="cash" {{ old('method') === 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="wallet" {{ old('method') === 'wallet' ? 'selected' : '' }}>Wallet</option>
                                            <option value="paypal" {{ old('method') === 'paypal' ? 'selected' : '' }}>PayPal</option>
                                        </select>
                                        @error('method')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="reference">Reference</label>
                                        <input
                                            type="text"
                                            name="reference"
                                            id="reference"
                                            class="form-control @error('reference') is-invalid @enderror"
                                            placeholder="Account number, PayPal email, etc."
                                            value="{{ old('reference') }}"
                                        />
                                        @error('reference')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="note">Note</label>
                                    <textarea
                                        name="note"
                                        id="note"
                                        class="form-control @error('note') is-invalid @enderror"
                                        rows="3"
                                        placeholder="Additional notes (optional)"
                                    >{{ old('note') }}</textarea>
                                    @error('note')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary" {{ $wallet->available_amount <= 0 ? 'disabled' : '' }}>
                                    Submit Withdrawal Request
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Withdrawal History -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Withdrawal History</h5>
                            <a href="{{ route('farmer.wallet.export', request()->all()) }}" class="btn btn-sm btn-primary">
                                <i class="bx bx-export me-1"></i> Export CSV
                            </a>
                        </div>

                        <!-- Filter Section -->
                        <div class="card-body" style="max-height: 320px; overflow-y: auto;">
                            <form method="GET" action="{{ route('farmer.wallet.index') }}" class="mb-4">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="search" class="form-label">Search</label>
                                        <input type="text" class="form-control" id="search" name="search"
                                               placeholder="Search by reference or note" value="{{ request('search') }}">
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
                                        <a href="{{ route('farmer.wallet.index') }}" class="btn btn-secondary" style="height: 48px;padding-top: 12px">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive text-nowrap">
                            <table class="table">
                                <thead>
                                <tr class="text-nowrap">
                                    <th>#</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Reference</th>
                                    <th>Note</th>
                                    <th>Rejection Reason</th>
                                    <th>Processed By</th>
                                    <th>Processed At</th>
                                    <th>Created Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($withdrawals as $index => $withdrawal)
                                    <tr>
                                        <th scope="row">{{ ($withdrawals->currentPage() - 1) * $withdrawals->perPage() + $index + 1 }}</th>
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
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No withdrawal requests found</td>
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
                                    <form method="GET" action="{{ route('farmer.wallet.index') }}" class="d-inline-flex align-items-center">
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

