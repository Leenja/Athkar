<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'starts_at' => 'nullable|date_format:H:i',
            'ends_at' => 'nullable|date_format:H:i',
            'order' => 'nullable|integer|min:0',
        ]);

        if (! isset($validated['order'])) {
            $validated['order'] = Category::max('order') + 1;
        }

        $category = Category::create($validated);

        return response()->json([
            'message' => 'Category has beed added successfully',
            'category' => $category,
        ], 201);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name_ar' => 'sometimes|string|max:255',
            'name_en' => 'sometimes|nullable|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:categories,slug,' . $category->id,
            'starts_at' => 'sometimes|nullable|date_format:H:i',
            'ends_at' => 'sometimes|nullable|date_format:H:i',
            'order' => 'sometimes|integer|min:0',
        ]);

        $category->update($validated);

        return response()->json([
            'message' => 'Category has beed updated successfully',
            'category' => $category->fresh(),
        ]);
    }

    public function destroy(Category $category)
    {
        $dhikrsCount = $category->dhikrs()->count();

        $category->delete();

        return response()->json([
            'message' => "The category was successfully deleted, and {$dhikrsCount} associated dhikrs were automatically deleted."
        ]);
    }
}
