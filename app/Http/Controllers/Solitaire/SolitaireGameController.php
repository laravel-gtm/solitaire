<?php

namespace App\Http\Controllers\Solitaire;

use App\Actions\Solitaire\CreateGameAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\SolitaireGameResource;
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
            'game' => new SolitaireGameResource($game),
        ]);
    }
}
