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

    <title>Edit Product</title>

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
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Edit Product</h5>
                                    <a href="{{ route('farmer.product.index') }}" class="btn btn-secondary">Back</a>
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

                                    <form method="post" action="{{ route('farmer.product.update', $product->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="product-name">Name</label>
                                                <input
                                                    name="name"
                                                    type="text"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    id="product-name"
                                                    value="{{ old('name', $product->name) }}"
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
                                                    value="{{ old('slug', $product->slug) }}"
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
                                                    value="{{ old('price', $product->price) }}"
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
                                                    value="{{ old('cost_price', $product->cost_price) }}"
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
                                                    value="{{ old('stock', $product->stock) }}"
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
                                                    value="{{ old('sku', $product->sku) }}"
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
                                                    <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                                                    @php
                                                        $selectedCategories = collect(old('categories', $product->categories->pluck('id')->toArray()));
                                                    @endphp
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ $selectedCategories->contains($category->id) ? 'selected' : '' }}>
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
                                            >{{ old('description', $product->description) }}</textarea>
                                            @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Current Image</label>
                                                <div>
                                                    @if($product->image)
                                                        <img
                                                            src="{{ asset('storage/' . $product->image) }}"
                                                            alt="Product Image"
                                                            class="img-thumbnail"
                                                            style="max-height: 120px;"
                                                        >
                                                    @else
                                                        <span>-</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="product-image">Upload New Image (optional)</label>
                                                <input
                                                    type="file"
                                                    name="image"
                                                    id="product-image"
                                                    class="form-control @error('image') is-invalid @enderror"
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
                                                @php
                                                    $existingColors = $product->colors ?? collect();
                                                    $colorCount = max(1, $existingColors->count());
                                                @endphp
                                                @for($i = 0; $i < $colorCount; $i++)
                                                    @php
                                                        $color = $existingColors[$i] ?? null;
                                                    @endphp
                                                    <div class="row g-2 color-row mt-2">
                                                        <div class="col-md-3">
                                                            <input type="text" name="color_name[]" class="form-control" placeholder="Color name" value="{{ old('color_name.' . $i, $color->name ?? '') }}" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="text" name="color_hex[]" class="form-control" placeholder="#FFFFFF" value="{{ old('color_hex.' . $i, $color->hex_code ?? '') }}" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="number" name="color_stock[]" class="form-control" placeholder="Stock" min="0" value="{{ old('color_stock.' . $i, $color->stock ?? 0) }}" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <select name="color_status[]" class="form-select">
                                                                <option value="active" {{ old('color_status.' . $i, $color->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                                                <option value="inactive" {{ old('color_status.' . $i, $color->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-1 d-flex align-items-center">
                                                            <button type="button" class="btn btn-sm btn-outline-danger remove-color-row">X</button>
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Update Product</button>
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

