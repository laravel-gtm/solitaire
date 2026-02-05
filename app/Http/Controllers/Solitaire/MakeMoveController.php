<?php

namespace App\Http\Controllers\Solitaire;

use App\Actions\Solitaire\MakeMoveAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Solitaire\MakeMoveRequest;
use App\Models\SolitaireGame;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class MakeMoveController extends Controller
{
    public function __invoke(
        MakeMoveRequest $request,
        SolitaireGame $game,
        MakeMoveAction $makeMoveAction,
    ): JsonResponse {
        try {
            $game = $makeMoveAction->execute(
                game: $game,
                from: $request->getFromLocation(),
                to: $request->getToLocation(),
                cards: $request->getCards(),
            );

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
