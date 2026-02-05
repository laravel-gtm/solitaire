<?php

namespace App\Http\Controllers\Solitaire;

use App\Http\Controllers\Controller;
use App\Models\SolitaireGame;
use App\Services\Solitaire\UndoService;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class UndoMoveController extends Controller
{
    public function __invoke(
        SolitaireGame $game,
        UndoService $undoService,
    ): JsonResponse {
        try {
            $game = $undoService->undo($game);

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
