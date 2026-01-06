<?php

namespace App\Http\Controllers\Farmer\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
            'category_id' => 'nullable|integer|exists:categories,id',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = Product::query()
            ->with(['categories', 'colors'])
            ->where('created_by', Auth::guard('farmer')->id());

        if (!empty($validated['search'])) {
            $searchTerm = $validated['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('slug', 'like', '%' . $searchTerm . '%')
                    ->orWhere('sku', 'like', '%' . $searchTerm . '%');
            });
        }

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['category_id'])) {
            $query->whereHas('categories', function ($q) use ($validated) {
                $q->where('categories.id', $validated['category_id']);
            });
        }

        if (!empty($validated['price_min'])) {
            $query->where('price', '>=', $validated['price_min']);
        }

        if (!empty($validated['price_max'])) {
            $query->where('price', '<=', $validated['price_max']);
        }

        if (!empty($validated['from_date'])) {
            $query->whereDate('created_at', '>=', $validated['from_date']);
        }

        if (!empty($validated['to_date'])) {
            $query->whereDate('created_at', '<=', $validated['to_date']);
        }

        $perPage = $validated['per_page'] ?? 10;

        $products = $query->paginate($perPage)->appends($request->all());
        $categories = Category::query()->orderBy('name')->get();

        return view('Farmer.Product.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:products,slug',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'cost_price' => 'nullable|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'sku' => 'nullable|string|max:255|unique:products,sku',
                'status' => 'required|in:active,inactive',
                'categories' => 'nullable|array',
                'categories.*' => 'integer|exists:categories,id',
                'image' => 'nullable|image|max:10240',
                'color_name' => 'nullable|array',
                'color_name.*' => 'nullable|string|max:100',
                'color_hex' => 'nullable|array',
                'color_hex.*' => 'nullable|string|max:7',
                'color_stock' => 'nullable|array',
                'color_stock.*' => 'nullable|integer|min:0',
                'color_status' => 'nullable|array',
                'color_status.*' => 'nullable|in:active,inactive',
            ]);

            $slug = $validatedData['slug'] ?? Str::slug($validatedData['name']);

            if (Product::query()->where('slug', $slug)->exists()) {
                return redirect()->back()->withInput()->withErrors([
                    'slug' => 'The slug has already been taken.',
                ]);
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imageName = $request->file('image')->getClientOriginalName();
                $imagePath = $request->file('image')->storeAs('ProductImage', $imageName, 'public');
            }

            $product = Product::query()->create([
                'name' => $validatedData['name'],
                'slug' => $slug,
                'description' => $validatedData['description'] ?? null,
                'price' => $validatedData['price'],
                'cost_price' => $validatedData['cost_price'] ?? null,
                'stock' => $validatedData['stock'],
                'sku' => $validatedData['sku'] ?? null,
                'status' => $validatedData['status'],
                'image' => $imagePath,
                'created_by' => Auth::guard('farmer')->id(),
            ]);

            if (!empty($validatedData['categories'])) {
                $product->categories()->sync($validatedData['categories']);
            }

            $this->syncColors($product, $validatedData);

            return redirect()->route('farmer.product.index')->with('success_message_create', 'Product created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error_message_create', 'Failed to create product: ' . $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        try {
            $product = Product::query()
                ->where('created_by', Auth::guard('farmer')->id())
                ->with(['categories', 'colors'])
                ->findOrFail($id);

            $categories = Category::query()->orderBy('name')->get();

            return view('Farmer.Product.edit', compact('product', 'categories'));
        } catch (\Exception $e) {
            return redirect()->route('farmer.product.index')->with('error_message', 'Product not found: ' . $e->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $product = Product::query()
                ->where('created_by', Auth::guard('farmer')->id())
                ->findOrFail($id);
            
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:products,slug,' . $id,
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'cost_price' => 'nullable|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'sku' => [
                    'nullable',
                    'string',
                    'max:255',
                    Rule::unique('products', 'sku')->ignore($id),
                ],
                'status' => 'required|in:active,inactive',
                'categories' => 'nullable|array',
                'categories.*' => 'integer|exists:categories,id',
                'image' => 'nullable|image|max:10240',
                'color_name' => 'nullable|array',
                'color_name.*' => 'nullable|string|max:100',
                'color_hex' => 'nullable|array',
                'color_hex.*' => 'nullable|string|max:7',
                'color_stock' => 'nullable|array',
                'color_stock.*' => 'nullable|integer|min:0',
                'color_status' => 'nullable|array',
                'color_status.*' => 'nullable|in:active,inactive',
            ]);

            $slug = $validatedData['slug'] ?? Str::slug($validatedData['name']);

            if (Product::query()->where('slug', $slug)->where('id', '<>', $product->id)->exists()) {
                return redirect()->back()->withInput()->withErrors([
                    'slug' => 'The slug has already been taken.',
                ]);
            }
            
            $updateData = [
                'name' => $validatedData['name'],
                'slug' => $slug,
                'description' => $validatedData['description'] ?? null,
                'price' => $validatedData['price'],
                'cost_price' => $validatedData['cost_price'] ?? null,
                'stock' => $validatedData['stock'],
                'sku' => $validatedData['sku'] ?? null,
                'status' => $validatedData['status'],
            ];
            
            if ($request->hasFile('image')) {
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                
                $imageName = $request->file('image')->getClientOriginalName();
                $path = $request->file('image')->storeAs('ProductImage', $imageName, 'public');
                $updateData['image'] = $path;
            }
            
            $product->update($updateData);

            if (array_key_exists('categories', $validatedData)) {
                $product->categories()->sync($validatedData['categories'] ?? []);
            }

            $this->syncColors($product, $validatedData, true);
            
            return redirect()->route('farmer.product.index')->with('success_message', 'Product updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error_message', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $product = Product::query()
                ->where('created_by', Auth::guard('farmer')->id())
                ->findOrFail($id);

            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $product->categories()->detach();
            $product->colors()->delete();
            $product->delete();
            
            return redirect()->route('farmer.product.index')->with('success_message', 'Product deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('farmer.product.index')->with('error_message', 'Failed to delete product: ' . $e->getMessage());
           }
    }
   
    public function export(Request $request)
    {
           try {
                $validated = $request->validate([
                    'search' => 'nullable|string|max:255',
                    'status' => 'nullable|in:active,inactive',
                    'category_id' => 'nullable|integer|exists:categories,id',
                    'price_min' => 'nullable|numeric|min:0',
                    'price_max' => 'nullable|numeric|min:0',
                    'from_date' => 'nullable|date',
                    'to_date' => 'nullable|date|after_or_equal:from_date',
                ]);

              $query = Product::query()
                    ->with(['categories'])
                    ->where('created_by', Auth::guard('farmer')->id());

              if (!empty($validated['search'])) {
                  $searchTerm = $validated['search'];
                   $query->where(function ($q) use ($searchTerm) {
                       $q->where('name', 'like', '%' . $searchTerm . '%')
                          ->orWhere('slug', 'like', '%' . $searchTerm . '%')
                          ->orWhere('sku', 'like', '%' . $searchTerm . '%');
                   });
               }
   
              if (!empty($validated['status'])) {
                  $query->where('status', $validated['status']);
              }

              if (!empty($validated['category_id'])) {
                  $query->whereHas('categories', function ($q) use ($validated) {
                      $q->where('categories.id', $validated['category_id']);
                  });
               }
   
              if (!empty($validated['price_min'])) {
                  $query->where('price', '>=', $validated['price_min']);
              }

              if (!empty($validated['price_max'])) {
                  $query->where('price', '<=', $validated['price_max']);
               }
   
              if (!empty($validated['from_date'])) {
                  $query->whereDate('created_at', '>=', $validated['from_date']);
               }
   
              if (!empty($validated['to_date'])) {
                  $query->whereDate('created_at', '<=', $validated['to_date']);
               }
   
              $products = $query->get();
   
              $filename = 'products_export_' . date('Y-m-d_H-i-s') . '.csv';
               $headers = [
                   'Content-Type' => 'text/csv',
                   'Content-Disposition' => 'attachment; filename="' . $filename . '"',
               ];
   
              $callback = function () use ($products) {
                   $handle = fopen('php://output', 'w');
                   fputcsv($handle, [
                      'ID', 'Name', 'Slug', 'SKU', 'Price', 'Cost Price', 'Stock', 'Status', 'Categories', 'Created At', 'Updated At'
                   ]);
   
                  foreach ($products as $product) {
                      $categories = $product->categories->pluck('name')->implode(', ');
                       fputcsv($handle, [
                          $product->id,
                          $product->name,
                          $product->slug,
                          $product->sku,
                          $product->price,
                          $product->cost_price,
                          $product->stock,
                          ucfirst($product->status),
                          $categories ?: 'N/A',
                          $product->created_at?->format('Y-m-d H:i:s'),
                          $product->updated_at?->format('Y-m-d H:i:s'),
                       ]);
                   }
   
                   fclose($handle);
               };
   
               return response()->stream($callback, 200, $headers);
           } catch (\Exception $e) {
              return redirect()->back()->with('error_message', 'Failed to export products: ' . $e->getMessage());
           }
    }

    public function updatePassword()
    {
        abort(404);
    }

    private function syncColors(Product $product, array $validatedData, bool $replaceExisting = false): void
    {
        $names = $validatedData['color_name'] ?? [];
        $hexes = $validatedData['color_hex'] ?? [];
        $stocks = $validatedData['color_stock'] ?? [];
        $statuses = $validatedData['color_status'] ?? [];

        $colors = [];
        foreach ($names as $index => $name) {
            if (empty($name)) {
                continue;
            }

            $colors[] = [
                'name' => $name,
                'hex_code' => $hexes[$index] ?? null,
                'stock' => $stocks[$index] ?? 0,
                'status' => $statuses[$index] ?? 'active',
            ];
        }

        if ($replaceExisting) {
            $product->colors()->delete();
        }

        if (count($colors) > 0) {
            $product->colors()->createMany($colors);
           }
    }
}
