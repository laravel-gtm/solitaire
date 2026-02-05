<?php

namespace App\Http\Controllers\Solitaire;

use App\Actions\Solitaire\CreateGameAction;
use App\Http\Controllers\Controller;
use App\Models\SolitaireGame;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SolitaireGameController extends Controller
{
    public function store(CreateGameAction $createGameAction): RedirectResponse
    {
        $game = $createGameAction->execute();

        return redirect()->route('solitaire.show', $game);
    }

    public function show(SolitaireGame $game): Response
    {
        return Inertia::render('Solitaire/Game', [
            'game' => [
                'id' => $game->id,
                'status' => $game->status->value,
                'moveCount' => $game->move_count,
                'score' => $game->score,
                'state' => $game->state,
                'canUndo' => ! empty($game->move_history),
            ],
        ]);
    }
}
