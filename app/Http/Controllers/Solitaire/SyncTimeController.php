<?php

namespace App\Http\Controllers\Solitaire;

use App\Http\Controllers\Controller;
use App\Models\SolitaireGame;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SyncTimeController extends Controller
{
    public function __invoke(Request $request, SolitaireGame $game): JsonResponse
    {
        $validated = $request->validate([
            'elapsed_seconds' => ['required', 'integer', 'min:0'],
        ]);

        $game->update([
            'elapsed_seconds' => $validated['elapsed_seconds'],
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
