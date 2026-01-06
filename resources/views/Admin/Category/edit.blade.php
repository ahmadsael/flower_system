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

    <title>Edit Category</title>

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
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Edit Category</h5>
                                    <a href="{{ route('admin.category.index') }}" class="btn btn-secondary">Back</a>
                                </div>
                                <div class="card-body">

                                    {{-- message Section --}}
                                    @if (session('error_message'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ session('error_message') }}
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

                                    <form method="post" action="{{ route('admin.category.update', $category->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

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
                                                        value="{{ old('name', $category->name) }}"
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
                                                        value="{{ old('slug', $category->slug) }}"
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
                                                        <option value="" disabled>Select Status</option>
                                                        <option value="active" {{ old('status', $category->status) === 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="inactive" {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                                                            <option
                                                                value="{{ $parent->id }}"
                                                                {{ (string) old('parent_id', $category->parent_id) === (string) $parent->id ? 'selected' : '' }}
                                                            >
                                                                {{ $parent->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('parent_id')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Current Image -->
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Current Image</label>
                                                <div>
                                                    @if($category->image)
                                                        <img
                                                            src="{{ asset('storage/' . $category->image) }}"
                                                            alt="Category Image"
                                                            class="img-thumbnail"
                                                            style="max-height: 100px;"
                                                        >
                                                    @else
                                                        <span>-</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- New Image -->
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="category-image">Upload New Image (optional)</label>
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
                                            >{{ old('description', $category->description) }}</textarea>
                                            @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary">Update Category</button>
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

