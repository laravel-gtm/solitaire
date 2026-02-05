<?php

namespace App\Actions\Solitaire;

use App\DTOs\GameState;
use App\Models\SolitaireGame;
use App\Services\Solitaire\ScoreCalculator;
use InvalidArgumentException;

class ResetStockAction
{
    public function __construct(
        private ScoreCalculator $scoreCalculator,
    ) {}

    public function execute(SolitaireGame $game): SolitaireGame
    {
        if (! $game->isPlaying()) {
            throw new InvalidArgumentException('Game is not in playing state');
        }

        $state = $game->state;

        if (! empty($state->stock)) {
            throw new InvalidArgumentException('Stock is not empty');
        }

        if (empty($state->waste)) {
            throw new InvalidArgumentException('Waste is empty');
        }

        $waste = $state->waste;
        $stock = [];

        while (! empty($waste)) {
            $card = array_pop($waste);
            $card = $card->withFaceUp(false);
            $stock[] = $card;
        }

        $newState = new GameState(
            stock: $stock,
            waste: [],
            foundations: $state->foundations,
            tableaus: $state->tableaus,
        );

        $game->state = $newState;
        $game->score = max(0, $game->score + $this->scoreCalculator->calculateRecycleWaste());
        $game->save();

        return $game;
    }
}
