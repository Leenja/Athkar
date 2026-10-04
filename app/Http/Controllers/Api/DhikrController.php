<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Dhikr;
use Illuminate\Http\Request;

class DhikrController extends Controller
{
    public function getDhikrs()
    {
        return Dhikr::with('category')->orderBy('category_id')->orderBy('order')->get();
    }

    public function show(Dhikr $dhikr)
    {
        return $dhikr->load('category');
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'text_ar' => 'required|string',
            'repeat_count' => 'nullable|integer|min:1',
            'audio_url' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'license' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        if (! isset($validated['order'])) {
            $validated['order'] = Dhikr::where('category_id', $validated['category_id'])->max('order') + 1;
        }

        $dhikr = Dhikr::create($validated);

        return response()->json([
            'message' => 'Dhikr has beed added successfully!',
            'dhikr' => $dhikr->load('category'),
        ], 201);
    }

    public function update(Request $request, Dhikr $dhikr)
    {
        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'text_ar' => 'sometimes|string',
            'repeat_count' => 'sometimes|integer|min:1',
            'audio_url' => 'sometimes|nullable|string|max:255',
            'source' => 'sometimes|nullable|string|max:255',
            'license' => 'sometimes|nullable|string|max:255',
            'order' => 'sometimes|integer|min:0',
        ]);

        $dhikr->update($validated);

        return response()->json([
            'message' => 'Dhikr has been updated successfully',
            'dhikr' => $dhikr->fresh()->load('category'),
        ]);
    }

    public function destroy(Dhikr $dhikr)
    {
        $dhikr->delete();

        return response()->json([
            'message' => 'Dhikr has been deleted successfully',
        ]);
    }
}
