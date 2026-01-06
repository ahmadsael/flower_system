<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
            'parent_id' => 'nullable|integer|exists:categories,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = Category::query()->with(['parent', 'createdBy']);

        if (!empty($validated['search'])) {
            $searchTerm = $validated['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('slug', 'like', '%' . $searchTerm . '%');
            });
        }

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['parent_id'])) {
            $query->where('parent_id', $validated['parent_id']);
        }

        if (!empty($validated['from_date'])) {
            $query->whereDate('created_at', '>=', $validated['from_date']);
        }

        if (!empty($validated['to_date'])) {
            $query->whereDate('created_at', '<=', $validated['to_date']);
        }

        $perPage = $validated['per_page'] ?? 10;

        $categories = $query->paginate($perPage)->appends($request->all());
        $parentCategories = Category::query()
            ->orderBy('name')
            ->get();

        return view('admin.Category.index', compact('categories', 'parentCategories'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:categories,slug',
                'description' => 'nullable|string',
                'status' => 'required|in:active,inactive',
                'parent_id' => 'nullable|exists:categories,id',
                'image' => 'nullable|image|max:10240',
            ]);

            $slug = $validatedData['slug'] ?? Str::slug($validatedData['name']);

            if (Category::query()->where('slug', $slug)->exists()) {
                return redirect()->back()->withInput()->withErrors([
                    'slug' => 'The slug has already been taken.',
                ]);
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imageName = $request->file('image')->getClientOriginalName();
                $imagePath = $request->file('image')->storeAs('CategoryImage', $imageName, 'public');
            }

            Category::query()->create([
                'name' => $validatedData['name'],
                'slug' => $slug,
                'description' => $validatedData['description'] ?? null,
                'status' => $validatedData['status'],
                'parent_id' => $validatedData['parent_id'] ?? null,
                'image' => $imagePath,
                'created_by' => Auth::guard('admin')->user()->id,
            ]);

            return redirect()->route('admin.category.index')->with('success_message_create', 'Category created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error_message_create', 'Failed to create category: ' . $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        try {
            $category = Category::query()->findOrFail($id);

            $parentCategories = Category::query()
                ->where('id', '<>', $category->id)
                ->orderBy('name')
                ->get();

            return view('Admin.Category.edit', compact('category', 'parentCategories'));
        } catch (\Exception $e) {
            return redirect()->route('admin.category.index')->with('error_message', 'Category not found: ' . $e->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $category = Category::query()->findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:categories,slug,' . $id,
                'description' => 'nullable|string',
                'status' => 'required|in:active,inactive',
                'parent_id' => 'nullable|exists:categories,id',
                'image' => 'nullable|image|max:10240',
            ]);

            $slug = $validatedData['slug'] ?? Str::slug($validatedData['name']);

            if (Category::query()->where('slug', $slug)->where('id', '<>', $category->id)->exists()) {
                return redirect()->back()->withInput()->withErrors([
                    'slug' => 'The slug has already been taken.',
                ]);
            }

            $updateData = [
                'name' => $validatedData['name'],
                'slug' => $slug,
                'description' => $validatedData['description'] ?? null,
                'status' => $validatedData['status'],
                'parent_id' => $validatedData['parent_id'] ?? null,
            ];

            if ($request->hasFile('image')) {
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }

                $imageName = $request->file('image')->getClientOriginalName();
                $path = $request->file('image')->storeAs('CategoryImage', $imageName, 'public');
                $updateData['image'] = $path;
            }

            $category->update($updateData);

            return redirect()->route('admin.category.index')->with('success_message', 'Category updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error_message', 'Failed to update category: ' . $e->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $category = Category::query()->findOrFail($id);

            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            $category->delete();

            return redirect()->route('admin.category.index')->with('success_message', 'Category deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.category.index')->with('error_message', 'Failed to delete category: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $validated = $request->validate([
                'search' => 'nullable|string|max:255',
                'status' => 'nullable|in:active,inactive',
                'parent_id' => 'nullable|integer|exists:categories,id',
                'from_date' => 'nullable|date',
                'to_date' => 'nullable|date|after_or_equal:from_date',
            ]);

            $query = Category::query()->with(['parent', 'createdBy']);

            if (!empty($validated['search'])) {
                $searchTerm = $validated['search'];
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('slug', 'like', '%' . $searchTerm . '%');
                });
            }

            if (!empty($validated['status'])) {
                $query->where('status', $validated['status']);
            }

            if (!empty($validated['parent_id'])) {
                $query->where('parent_id', $validated['parent_id']);
            }

            if (!empty($validated['from_date'])) {
                $query->whereDate('created_at', '>=', $validated['from_date']);
            }

            if (!empty($validated['to_date'])) {
                $query->whereDate('created_at', '<=', $validated['to_date']);
            }

            $categories = $query->get();

            $filename = 'categories_export_' . date('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = static function () use ($categories) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, [
                    'ID',
                    'Name',
                    'Slug',
                    'Status',
                    'Parent Category',
                    'Created By',
                    'Created At',
                    'Updated At',
                ]);

                foreach ($categories as $category) {
                    fputcsv($handle, [
                        $category->id,
                        $category->name,
                        $category->slug,
                        ucfirst($category->status),
                        $category->parent?->name ?? 'N/A',
                        $category->createdBy?->name ?? 'N/A',
                        $category->created_at?->format('Y-m-d H:i:s'),
                        $category->updated_at?->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return redirect()->back()->with('error_message', 'Failed to export categories: ' . $e->getMessage());
        }
    }
}
