<?php

namespace App\Http\Controllers\Solitaire;

use App\Actions\Solitaire\ResetStockAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\SolitaireGameResource;
use App\Models\SolitaireGame;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class ResetStockController extends Controller
{
    public function __invoke(
        SolitaireGame $game,
        ResetStockAction $resetStockAction,
    ): JsonResponse {
        try {
            $game = $resetStockAction->execute($game);

            return response()->json([
                'success' => true,
                'game' => new SolitaireGameResource($game),
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
