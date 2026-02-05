<?php

namespace App\Http\Controllers\Solitaire;

use App\Actions\Solitaire\MakeMoveAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Solitaire\MakeMoveRequest;
use App\Http\Resources\SolitaireGameResource;
use App\Models\SolitaireGame;
use App\Services\Solitaire\AutoCompleteService;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class MakeMoveController extends Controller
{
    public function __invoke(
        MakeMoveRequest $request,
        SolitaireGame $game,
        MakeMoveAction $makeMoveAction,
        AutoCompleteService $autoCompleteService,
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
