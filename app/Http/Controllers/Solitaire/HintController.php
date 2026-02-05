<?php

namespace App\Http\Controllers\Solitaire;

use App\Http\Controllers\Controller;
use App\Models\SolitaireGame;
use App\Services\Solitaire\HintGenerator;
use Illuminate\Http\JsonResponse;

class HintController extends Controller
{
    public function __invoke(
        SolitaireGame $game,
        HintGenerator $hintGenerator,
    ): JsonResponse {
        if (! $game->isPlaying()) {
            return response()->json([
                'success' => false,
                'error' => 'Game is not in playing state',
            ], 422);
        }

        $hint = $hintGenerator->findHint($game->state);

        if ($hint === null) {
            // Check if we should suggest drawing
            $shouldDraw = ! empty($game->state->stock);

            return response()->json([
                'success' => true,
                'hint' => null,
                'shouldDraw' => $shouldDraw,
            ]);
        }

        return response()->json([
            'success' => true,
            'hint' => [
                'from' => $hint['from'],
                'to' => $hint['to'],
                'cards' => $hint['cards'],
            ],
            'shouldDraw' => false,
        ]);
    }
}
