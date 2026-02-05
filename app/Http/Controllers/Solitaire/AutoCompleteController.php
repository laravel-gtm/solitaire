<?php

namespace App\Http\Controllers\Solitaire;

use App\Http\Controllers\Controller;
use App\Models\SolitaireGame;
use App\Services\Solitaire\AutoCompleteService;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class AutoCompleteController extends Controller
{
    public function __invoke(
        SolitaireGame $game,
        AutoCompleteService $autoCompleteService,
    ): JsonResponse {
        try {
            $game = $autoCompleteService->autoComplete($game);

            return response()->json([
                'success' => true,
                'game' => [
                    'id' => $game->id,
                    'status' => $game->status->value,
                    'moveCount' => $game->move_count,
                    'score' => $game->score,
                    'state' => $game->state,
                    'canAutoComplete' => false,
                ],
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
