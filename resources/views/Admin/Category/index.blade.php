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

    <title>Category Manage</title>

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

                    <!-- Create Category -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Create Category</h5>
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

                            <form method="post" action="{{ route('admin.category.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <!-- Name -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="category-name">Name</label>
                                        <div class="input-group input-group-merge">
                                            <input
                                                name="name"
                                                type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                id="category-name"
                                                placeholder="Category name"
                                                value="{{ old('name') }}"
                                                required
                                            />
                                        </div>
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Slug -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="category-slug">Slug (optional)</label>
                                        <div class="input-group input-group-merge">
                                            <input
                                                type="text"
                                                name="slug"
                                                id="category-slug"
                                                class="form-control @error('slug') is-invalid @enderror"
                                                placeholder="category-slug"
                                                value="{{ old('slug') }}"
                                            />
                                        </div>
                                        @error('slug')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Status -->
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="category-status">Status</label>
                                        <div class="input-group input-group-merge">
                                            <select
                                                name="status"
                                                id="category-status"
                                                class="form-control @error('status') is-invalid @enderror"
                                                required
                                            >
                                                <option value="" selected disabled>Select Status</option>
                                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                        @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Parent Category -->
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="parent-id">Parent Category</label>
                                        <div class="input-group input-group-merge">
                                            <select
                                                name="parent_id"
                                                id="parent-id"
                                                class="form-control @error('parent_id') is-invalid @enderror"
                                            >
                                            <option value="">None</option>
                                            @foreach($parentCategories as $parent)
                                                <option value="{{ $parent->id }}" {{ (string) old('parent_id') === (string) $parent->id ? 'selected' : '' }}>
                                                    {{ $parent->name }}
                                                </option>
                                            @endforeach
                                            </select>
                                        </div>
                                        @error('parent_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Image Upload Input -->
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="category-image">Upload Image</label>
                                        <div class="input-group">
                                            <input
                                                type="file"
                                                name="image"
                                                id="category-image"
                                                class="form-control @error('image') is-invalid @enderror"
                                                aria-label="Upload image"
                                                accept="image/*"
                                            />
                                        </div>
                                        <div class="form-text">Allowed formats: JPG, PNG, GIF</div>
                                        @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label class="form-label" for="category-description">Description</label>
                                    <textarea
                                        name="description"
                                        id="category-description"
                                        class="form-control @error('description') is-invalid @enderror"
                                        rows="3"
                                        placeholder="Category description (optional)"
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Create</button>
                            </form>
                        </div>
                    </div>

                    <!-- Category Table -->
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

                        <h5 class="card-header">Category Table</h5>

                        <!-- Filter Section -->
                        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                            <form method="GET" action="{{ route('admin.category.index') }}" class="mb-4">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="search" class="form-label">Search</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="search"
                                            name="search"
                                            placeholder="Search by name or slug"
                                            value="{{ request('search') }}"
                                        >
                                    </div>

                                    <div class="col-md-2">
                                        <label for="filter-status" class="form-label">Status</label>
                                        <select class="form-select" name="status" id="filter-status">
                                            <option value="">All</option>
                                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Not Active</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="filter-parent-id" class="form-label">Parent Category</label>
                                        <select class="form-select" name="parent_id" id="filter-parent-id">
                                            <option value="">All</option>
                                            @foreach($parentCategories as $parent)
                                                <option value="{{ $parent->id }}" {{ (string) request('parent_id') === (string) $parent->id ? 'selected' : '' }}>
                                                    {{ $parent->name }}
                                                </option>
                                            @endforeach
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

                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                                        <a href="{{ route('admin.category.index') }}" class="btn btn-secondary" style="height: 48px; padding-top: 12px">Reset</a>
                                    </div>

                                    <div class="col-md-2 d-flex align-items-end">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                Export
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.category.export', request()->all()) }}">
                                                        Export to Excel
                                                    </a>
                                                </li>
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
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Parent</th>
                                    <th>Image</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                    <th>Last Updated Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($categories as $index => $category)
                                    <tr>
                                        <th scope="row">{{ ($categories->currentPage() - 1) * $categories->perPage() + $index + 1 }}</th>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->slug }}</td>
                                        <td>
                                            @if ($category->status === 'active')
                                                <div class="badge bg-success">Active</div>
                                            @else
                                                <div class="badge bg-danger">Not Active</div>
                                            @endif
                                        </td>
                                        <td>{{ $category->parent?->name ?? '-' }}</td>
                                        <td>
                                            @if($category->image)
                                                <img
                                                    src="{{ asset('storage/' . $category->image) }}"
                                                    style="width: 80px; height: 80px;"
                                                    class="rounded"
                                                    alt="Category Image"
                                                >
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $category->createdBy->name ?? '-' }}</td>
                                        <td>{{ optional($category->created_at)->format('Y-m-d') }}</td>
                                        <td>{{ optional($category->updated_at)->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('admin.category.edit', $category->id) }}">
                                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                                    </a>
                                                    <form method="post" action="{{ route('admin.category.delete', $category->id) }}">
                                                        @csrf
                                                        @method('delete')
                                                        <button
                                                            class="dropdown-item btn"
                                                            type="submit"
                                                            onclick="return confirm('Are you sure you want to delete this category?')"
                                                        >
                                                            <i class="bx bx-trash me-1"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No categories found</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Section -->
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }} entries
                                </div>

                                <div>
                                    @if ($categories->hasPages())
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination pagination-round pagination-primary mb-0">
                                                {{-- Previous Page Link --}}
                                                @if ($categories->onFirstPage())
                                                    <li class="page-item disabled">
                                                        <a class="page-link" href="javascript:void(0);" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $categories->previousPageUrl() }}" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                @endif

                                                {{-- Pagination Elements --}}
                                                @foreach ($categories->getUrlRange(max(1, $categories->currentPage() - 2), min($categories->lastPage(), $categories->currentPage() + 2)) as $page => $url)
                                                    @if ($page == $categories->currentPage())
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
                                                @if ($categories->hasMorePages())
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $categories->nextPageUrl() }}" aria-label="Next">
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
                                    <form method="GET" action="{{ route('admin.category.index') }}" class="d-inline-flex align-items-center">
                                        @foreach(request()->except(['page', 'per_page']) as $key => $value)
                                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                        @endforeach
                                        <label class="me-2">Show</label>
                                        <select
                                            name="per_page"
                                            class="form-select form-select-sm"
                                            style="width: auto;"
                                            onchange="this.form.submit()"
                                        >
                                            @foreach([10, 25, 50, 100] as $perPage)
                                                <option value="{{ $perPage }}" {{ (int) request('per_page', 10) === $perPage ? 'selected' : '' }}>
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

                <!-- Footer -->
                <!-- add footer here  -->
                <!-- / Footer -->

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

