<?php

namespace App\Http\Controllers\Solitaire;

use App\Actions\Solitaire\DrawCardAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\SolitaireGameResource;
use App\Models\SolitaireGame;
use App\Services\Solitaire\AutoCompleteService;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class DrawCardController extends Controller
{
    public function __invoke(
        SolitaireGame $game,
        DrawCardAction $drawCardAction,
        AutoCompleteService $autoCompleteService,
    ): JsonResponse {
        try {
            $game = $drawCardAction->execute($game);

            return response()->json([
                'success' => true,
                'game' => new SolitaireGameResource($game),
                'canAutoComplete' => $autoCompleteService->canAutoComplete($game->state),
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
