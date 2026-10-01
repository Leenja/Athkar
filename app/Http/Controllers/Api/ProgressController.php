<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dhikr;
use App\Models\DhikrProgress;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function update(Request $request, Dhikr $dhikr)
    {
        $request->validate([
            'current_count' => 'required|integer|min:0',
        ]);

        $progress = DhikrProgress::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'dhikr_id' => $dhikr->id,
                'progress_date' => now()->toDateString(),
            ],
            [
                'current_count' => min($request->current_count, $dhikr->repeat_count),
            ]
        );

        return response()->json($progress);
    }

    public function today(Request $request)
    {
        return DhikrProgress::where('user_id', $request->user()->id)
            ->where('progress_date', now()->toDateString())
            ->get();
    }
}
