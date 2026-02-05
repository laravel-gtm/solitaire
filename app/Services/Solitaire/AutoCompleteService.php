<?php

namespace App\Services\Solitaire;

use App\DTOs\Card;
use App\DTOs\GameState;
use App\DTOs\MoveLocation;
use App\Models\SolitaireGame;
use InvalidArgumentException;

class AutoCompleteService
{
    public function __construct(
        private MoveValidator $moveValidator,
        private WinDetector $winDetector,
    ) {}

    /**
     * Check if auto-complete is available.
     * Auto-complete is available when all cards are face-up.
     */
    public function canAutoComplete(GameState $state): bool
    {
        if (! empty($state->stock)) {
            return false;
        }

        foreach ($state->tableaus as $tableau) {
            foreach ($tableau as $card) {
                if (! $card->faceUp) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Auto-complete the game by moving all remaining cards to foundations.
     */
    public function autoComplete(SolitaireGame $game): SolitaireGame
    {
        if (! $game->isPlaying()) {
            throw new InvalidArgumentException('Game is not in playing state');
        }

        $state = $game->state;

        if (! $this->canAutoComplete($state)) {
            throw new InvalidArgumentException('Auto-complete is not available');
        }

        $moved = true;
        while ($moved && ! $this->winDetector->isWon($state)) {
            $moved = false;

            if (! empty($state->waste)) {
                $card = $state->waste[count($state->waste) - 1];
                if ($this->canMoveToFoundation($state, $card)) {
                    $state = $this->moveToFoundation($state, $card, new MoveLocation(type: 'waste'));
                    $game->move_count++;
                    $moved = true;

                    continue;
                }
            }

            foreach ($state->tableaus as $tableauIndex => $tableau) {
                if (empty($tableau)) {
                    continue;
                }

                $card = $tableau[count($tableau) - 1];
                if ($this->canMoveToFoundation($state, $card)) {
                    $state = $this->moveToFoundation($state, $card, new MoveLocation(type: 'tableau', index: $tableauIndex));
                    $game->move_count++;
                    $moved = true;
                    break;
                }
            }
        }

        $game->state = $state;

        if ($this->winDetector->isWon($state)) {
            $game->status = \App\Enums\GameStatus::Won;
        }

        $game->save();

        return $game;
    }

    private function canMoveToFoundation(GameState $state, Card $card): bool
    {
        return $this->moveValidator->isValidFoundationMove($state, [$card]);
    }

    private function moveToFoundation(GameState $state, Card $card, MoveLocation $from): GameState
    {
        $stock = $state->stock;
        $waste = $state->waste;
        $foundations = $state->foundations;
        $tableaus = $state->tableaus;

        if ($from->isWaste()) {
            array_pop($waste);
        } elseif ($from->isTableau() && $from->index !== null) {
            array_pop($tableaus[(int) $from->index]);
        }

        $foundations[$card->suit][] = $card->withFaceUp(true);

        return new GameState(
            stock: $stock,
            waste: $waste,
            foundations: $foundations,
            tableaus: $tableaus,
        );
    }
}
