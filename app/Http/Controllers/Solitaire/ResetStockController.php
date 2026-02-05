<?php

namespace App\Http\Controllers\Solitaire;

use App\Actions\Solitaire\ResetStockAction;
use App\Http\Controllers\Controller;
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
                'game' => [
                    'id' => $game->id,
                    'status' => $game->status->value,
                    'moveCount' => $game->move_count,
                    'score' => $game->score,
                    'state' => $game->state,
                    'canUndo' => ! empty($game->move_history),
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
