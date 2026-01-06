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

    <title>Product Manage</title>

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

                    <!-- Create Product -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Create Product</h5>
                        </div>
                        <div class="card-body">

                            {{-- message Section --}}
                            @if (session('success_message_create'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success_message_create') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session('error_message_create'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error_message_create') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            {{-- end message Section --}}

                            <!-- Display Validation Error Messages -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="post" action="{{ route('farmer.product.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="product-name">Name</label>
                                        <input
                                            name="name"
                                            type="text"
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="product-name"
                                            placeholder="Bouquet"
                                            value="{{ old('name') }}"
                                            required
                                        />
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="product-slug">Slug (optional)</label>
                                        <input
                                            type="text"
                                            name="slug"
                                            id="product-slug"
                                            class="form-control @error('slug') is-invalid @enderror"
                                            placeholder="bouquet-slug"
                                            value="{{ old('slug') }}"
                                        />
                                        @error('slug')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="product-price">Price</label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            name="price"
                                            id="product-price"
                                            class="form-control @error('price') is-invalid @enderror"
                                            value="{{ old('price') }}"
                                            required
                                        />
                                        @error('price')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="product-cost-price">Cost Price (optional)</label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            name="cost_price"
                                            id="product-cost-price"
                                            class="form-control @error('cost_price') is-invalid @enderror"
                                            value="{{ old('cost_price') }}"
                                        />
                                        @error('cost_price')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="product-stock">Stock</label>
                                        <input
                                            type="number"
                                            name="stock"
                                            id="product-stock"
                                            class="form-control @error('stock') is-invalid @enderror"
                                            value="{{ old('stock', 0) }}"
                                            required
                                        />
                                        @error('stock')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="product-sku">SKU (optional)</label>
                                        <input
                                            type="text"
                                            name="sku"
                                            id="product-sku"
                                            class="form-control @error('sku') is-invalid @enderror"
                                            value="{{ old('sku') }}"
                                        />
                                        @error('sku')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="product-status">Status</label>
                                        <select
                                            name="status"
                                            id="product-status"
                                            class="form-control @error('status') is-invalid @enderror"
                                            required
                                        >
                                            <option value="" selected disabled>Select Status</option>
                                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="product-categories">Categories</label>
                                        <select
                                            name="categories[]"
                                            id="product-categories"
                                            class="form-select @error('categories') is-invalid @enderror"
                                            multiple
                                        >
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ collect(old('categories', []))->contains($category->id) ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
                                        @error('categories')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        @error('categories.*')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="product-description">Description</label>
                                    <textarea
                                        name="description"
                                        id="product-description"
                                        class="form-control @error('description') is-invalid @enderror"
                                        rows="3"
                                        placeholder="Product description (optional)"
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="product-image">Upload Image</label>
                                        <input
                                            type="file"
                                            name="image"
                                            id="product-image"
                                            class="form-control @error('image') is-invalid @enderror"
                                            aria-label="Upload image"
                                            accept="image/*"
                                        />
                                        <div class="form-text">Allowed formats: JPG, PNG, GIF</div>
                                        @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label mb-0">Colors (optional)</label>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-color-row">Add Color</button>
                                    </div>
                                    <div id="color-rows">
                                        <div class="row g-2 color-row mt-2">
                                            <div class="col-md-3">
                                                <input type="text" name="color_name[]" class="form-control" placeholder="Color name" />
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="color_hex[]" class="form-control" placeholder="#FFFFFF" />
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" name="color_stock[]" class="form-control" placeholder="Stock" min="0" />
                                            </div>
                                            <div class="col-md-3">
                                                <select name="color_status[]" class="form-select">
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-color-row">X</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Create</button>
                            </form>
                        </div>
                    </div>

                    <!-- Product Table -->
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

                        <h5 class="card-header">Product Table</h5>

                        <!-- Filter Section -->
                        <div class="card-body" style="max-height: 320px; overflow-y: auto;">
                            <form method="GET" action="{{ route('farmer.product.index') }}" class="mb-4">
                                <div class="row g-3">

                                        <div class="col-md-3">
                                            <label for="search" class="form-label">Search</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                   placeholder="Search by name, slug, or SKU" value="{{ request('search') }}">
                                        </div>
                                    <div class="col-md-2">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" name="status" id="status">
                                            <option value="">All</option>
                                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Not Active</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select class="form-select" name="category_id" id="category_id">
                                            <option value="">All</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ (string) request('category_id') === (string) $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="price_min" class="form-label">Price From</label>
                                        <input type="number" step="0.01" class="form-control" id="price_min" name="price_min" value="{{ request('price_min') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="price_max" class="form-label">Price To</label>
                                        <input type="number" step="0.01" class="form-control" id="price_max" name="price_max" value="{{ request('price_max') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="from_date" class="form-label">From Date</label>
                                        <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="to_date" class="form-label">To Date</label>
                                        <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                                    </div>

                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                                        <a href="{{ route('farmer.product.index') }}" class="btn btn-secondary" style="height: 48px;padding-top: 12px">Reset</a>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                Export
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                                <li><a class="dropdown-item" href="{{ route('farmer.product.export', request()->all()) }}">Export to Excel</a></li>
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
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Categories</th>
                                    <th>Image</th>
                                    <th>Created Date</th>
                                    <th>Last Updated Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($products as $index => $product)
                                    <tr>
                                        <th scope="row">{{ ($products->currentPage() - 1) * $products->perPage() + $index + 1 }}</th>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->sku ?? '-' }}</td>
                                        <td>{{ number_format($product->price, 2) }}</td>
                                        <td>{{ $product->stock }}</td>
                                        <td>
                                            @if ($product->status === 'active')
                                                <div class="badge bg-success">Active</div>
                                            @else
                                                <div class="badge bg-danger">Not Active</div>
                                            @endif
                                        </td>
                                      
                                        <td>
                                            {{ $product->categories->pluck('name')->implode(', ') ?: '-' }}
                                        </td>
                                        <td>
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                     style="width: 80px; height: 80px;" class="rounded" alt="Product Image">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ optional($product->created_at)->format('Y-m-d') }}</td>
                                        <td>{{ optional($product->updated_at)->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('farmer.product.edit' , $product->id) }}">
                                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                                    </a>
                                                    <form method="post" action="{{ route('farmer.product.delete' , $product->id) }}">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="dropdown-item btn" type="submit" onclick="return confirm('Are you sure you want to delete this product?')">
                                                            <i class="bx bx-trash me-1"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center">No products found</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Section -->
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} entries
                                </div>

                                <div>
                                    @if ($products->hasPages())
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination pagination-round pagination-primary mb-0">
                                                {{-- Previous Page Link --}}
                                                @if ($products->onFirstPage())
                                                    <li class="page-item disabled">
                                                        <a class="page-link" href="javascript:void(0);" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $products->previousPageUrl() }}" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                @endif

                                                {{-- Pagination Elements --}}
                                                @foreach ($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                                                    @if ($page == $products->currentPage())
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
                                                @if ($products->hasMorePages())
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $products->nextPageUrl() }}" aria-label="Next">
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
                                    <form method="GET" action="{{ route('farmer.product.index') }}" class="d-inline-flex align-items-center">
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('color-rows');
        const addBtn = document.getElementById('add-color-row');

        if (addBtn && container) {
            addBtn.addEventListener('click', function () {
                const row = container.querySelector('.color-row').cloneNode(true);
                row.querySelectorAll('input').forEach(input => input.value = '');
                row.querySelectorAll('select').forEach(select => select.value = 'active');
                container.appendChild(row);
            });

            container.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-color-row')) {
                    const rows = container.querySelectorAll('.color-row');
                    if (rows.length > 1) {
                        e.target.closest('.color-row').remove();
                    }
                }
            });
        }
    });
</script>

</body>
</html>

