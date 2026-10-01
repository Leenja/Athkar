<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dhikr;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->favorites()->with('category')->get();
    }

    public function store(Request $request, Dhikr $dhikr)
    {
        $request->user()->favorites()->syncWithoutDetaching([$dhikr->id]);

        return response()->json(['message' => 'Added To Favorites']);
    }

    public function destroy(Request $request, Dhikr $dhikr)
    {
        $request->user()->favorites()->detach($dhikr->id);

        return response()->json(['message' => 'Removed From Favorites']);
    }
}
