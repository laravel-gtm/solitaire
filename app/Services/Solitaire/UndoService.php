<?php

namespace App\Services\Solitaire;

use App\DTOs\GameState;
use App\DTOs\Move;
use App\Models\SolitaireGame;
use InvalidArgumentException;

class UndoService
{
    /**
     * Undo the last move in the game.
     */
    public function undo(SolitaireGame $game): SolitaireGame
    {
        $moveHistory = $game->move_history ?? [];

        if (empty($moveHistory)) {
            throw new InvalidArgumentException('No moves to undo');
        }

        $lastMove = array_pop($moveHistory);
        $newState = $this->reverseMove($game->state, $lastMove);

        $game->state = $newState;
        $game->move_count = max(0, $game->move_count - 1);
        $game->move_history = $moveHistory;
        $game->save();

        return $game;
    }

    /**
     * Reverse a move to restore the previous state.
     */
    private function reverseMove(GameState $state, Move $move): GameState
    {
        $stock = $state->stock;
        $waste = $state->waste;
        $foundations = $state->foundations;
        $tableaus = $state->tableaus;

        $to = $move->to;
        $from = $move->from;
        $cards = $move->cards;

        if ($to->isFoundation() && is_string($to->index)) {
            $foundations[$to->index] = array_slice($foundations[$to->index], 0, -count($cards));
        } elseif ($to->isFoundation()) {
            $suit = $cards[0]->suit;
            $foundations[$suit] = array_slice($foundations[$suit], 0, -count($cards));
        } elseif ($to->isTableau() && $to->index !== null) {
            $tableauIndex = (int) $to->index;
            $tableaus[$tableauIndex] = array_slice($tableaus[$tableauIndex], 0, -count($cards));
        }

        if ($from->isWaste()) {
            foreach (array_reverse($cards) as $card) {
                $waste[] = $card;
            }
        } elseif ($from->isFoundation() && is_string($from->index)) {
            foreach ($cards as $card) {
                $foundations[$from->index][] = $card;
            }
        } elseif ($from->isTableau() && $from->index !== null) {
            $tableauIndex = (int) $from->index;
            foreach ($cards as $card) {
                $tableaus[$tableauIndex][] = $card;
            }
        }

        return new GameState(
            stock: $stock,
            waste: $waste,
            foundations: $foundations,
            tableaus: $tableaus,
        );
    }
}
