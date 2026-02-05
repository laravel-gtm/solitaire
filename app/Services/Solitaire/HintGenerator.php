<?php

namespace App\Services\Solitaire;

use App\DTOs\Card;
use App\DTOs\GameState;
use App\DTOs\MoveLocation;

class HintGenerator
{
    public function __construct(
        private MoveValidator $moveValidator,
    ) {}

    /**
     * Find the best available hint for the current game state.
     *
     * @return array{from: MoveLocation, to: MoveLocation, cards: list<Card>}|null
     */
    public function findHint(GameState $state): ?array
    {
        // Priority 1: Move cards to foundations
        if ($hint = $this->findFoundationMove($state)) {
            return $hint;
        }

        // Priority 2: Move cards between tableaus
        if ($hint = $this->findTableauMove($state)) {
            return $hint;
        }

        // Priority 3: Move waste card to tableau
        if ($hint = $this->findWasteToTableauMove($state)) {
            return $hint;
        }

        // Priority 4: Draw from stock if available
        if (! empty($state->stock)) {
            return null; // Signal to draw
        }

        return null;
    }

    /**
     * @return array{from: MoveLocation, to: MoveLocation, cards: list<Card>}|null
     */
    private function findFoundationMove(GameState $state): ?array
    {
        // Check waste pile first
        if (! empty($state->waste)) {
            $card = $state->waste[count($state->waste) - 1];
            $from = MoveLocation::waste();
            $to = MoveLocation::foundation($card->suit);

            if ($this->moveValidator->isValidMove($state, $from, $to, [$card])) {
                return ['from' => $from, 'to' => $to, 'cards' => [$card]];
            }
        }

        // Check tableaus
        foreach ($state->tableaus as $tableauIndex => $tableau) {
            if (empty($tableau)) {
                continue;
            }

            $card = $tableau[count($tableau) - 1];
            if (! $card->faceUp) {
                continue;
            }

            $from = MoveLocation::tableau($tableauIndex);
            $to = MoveLocation::foundation($card->suit);

            if ($this->moveValidator->isValidMove($state, $from, $to, [$card])) {
                return ['from' => $from, 'to' => $to, 'cards' => [$card]];
            }
        }

        return null;
    }

    /**
     * @return array{from: MoveLocation, to: MoveLocation, cards: list<Card>}|null
     */
    private function findTableauMove(GameState $state): ?array
    {
        foreach ($state->tableaus as $fromIndex => $fromTableau) {
            if (empty($fromTableau)) {
                continue;
            }

            // Find the first face-up card in this tableau
            $faceUpStart = null;
            foreach ($fromTableau as $cardIndex => $card) {
                if ($card->faceUp) {
                    $faceUpStart = $cardIndex;
                    break;
                }
            }

            if ($faceUpStart === null) {
                continue;
            }

            // Try moving each sequence starting from face-up cards
            for ($startIndex = $faceUpStart; $startIndex < count($fromTableau); $startIndex++) {
                $cards = array_slice($fromTableau, $startIndex);

                // Don't suggest moving a King from an empty spot below it
                if ($startIndex === 0 && $cards[0]->rank === 13) {
                    continue;
                }

                $from = MoveLocation::tableau($fromIndex);

                foreach ($state->tableaus as $toIndex => $toTableau) {
                    if ($fromIndex === $toIndex) {
                        continue;
                    }

                    $to = MoveLocation::tableau($toIndex);

                    if ($this->moveValidator->isValidMove($state, $from, $to, $cards)) {
                        // Only suggest if it reveals a face-down card or moves to empty with King
                        $revealsCard = $startIndex > 0 && ! $fromTableau[$startIndex - 1]->faceUp;
                        $isKingToEmpty = empty($toTableau) && $cards[0]->rank === 13;

                        if ($revealsCard || $isKingToEmpty) {
                            return ['from' => $from, 'to' => $to, 'cards' => $cards];
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * @return array{from: MoveLocation, to: MoveLocation, cards: list<Card>}|null
     */
    private function findWasteToTableauMove(GameState $state): ?array
    {
        if (empty($state->waste)) {
            return null;
        }

        $card = $state->waste[count($state->waste) - 1];
        $from = MoveLocation::waste();

        foreach ($state->tableaus as $toIndex => $toTableau) {
            $to = MoveLocation::tableau($toIndex);

            if ($this->moveValidator->isValidMove($state, $from, $to, [$card])) {
                return ['from' => $from, 'to' => $to, 'cards' => [$card]];
            }
        }

        return null;
    }
}
