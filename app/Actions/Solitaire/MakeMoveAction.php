<?php

namespace App\Actions\Solitaire;

use App\DTOs\Card;
use App\DTOs\GameState;
use App\DTOs\Move;
use App\DTOs\MoveLocation;
use App\Enums\GameStatus;
use App\Models\SolitaireGame;
use App\Services\Solitaire\MoveValidator;
use App\Services\Solitaire\ScoreCalculator;
use App\Services\Solitaire\WinDetector;
use InvalidArgumentException;

class MakeMoveAction
{
    public function __construct(
        private MoveValidator $moveValidator,
        private WinDetector $winDetector,
        private ScoreCalculator $scoreCalculator,
    ) {}

    /**
     * @param  list<Card>  $cards
     */
    public function execute(SolitaireGame $game, MoveLocation $from, MoveLocation $to, array $cards): SolitaireGame
    {
        if (! $game->isPlaying()) {
            throw new InvalidArgumentException('Game is not in playing state');
        }

        $state = $game->state;

        if (! $this->moveValidator->canPickUp($state, $from, $cards)) {
            throw new InvalidArgumentException('Cannot pick up these cards');
        }

        if (! $this->moveValidator->isValidMove($state, $from, $to, $cards)) {
            throw new InvalidArgumentException('Invalid move');
        }

        $willFlipCard = $this->willFlipCard($state, $from, $cards);
        $newState = $this->applyMove($state, $from, $to, $cards);

        $move = new Move($from, $to, $cards);
        $moveHistory = $game->move_history ?? [];
        $moveHistory[] = $move;

        $scoreChange = $this->calculateScoreChange($to, $willFlipCard);

        $game->state = $newState;
        $game->move_count = $game->move_count + 1;
        $game->score = max(0, $game->score + $scoreChange);
        $game->move_history = $moveHistory;

        if ($this->winDetector->isWon($newState)) {
            $game->status = GameStatus::Won;
        }

        $game->save();

        return $game;
    }

    /**
     * @param  list<Card>  $cards
     */
    private function applyMove(GameState $state, MoveLocation $from, MoveLocation $to, array $cards): GameState
    {
        $stock = $state->stock;
        $waste = $state->waste;
        $foundations = $state->foundations;
        $tableaus = $state->tableaus;

        if ($from->isWaste()) {
            array_pop($waste);
        } elseif ($from->isFoundation() && is_string($from->index)) {
            array_pop($foundations[$from->index]);
        } elseif ($from->isTableau() && $from->index !== null) {
            $tableauIndex = (int) $from->index;
            $cardCount = count($cards);
            $tableaus[$tableauIndex] = array_slice($tableaus[$tableauIndex], 0, -$cardCount);

            if (! empty($tableaus[$tableauIndex])) {
                $lastIndex = count($tableaus[$tableauIndex]) - 1;
                $lastCard = $tableaus[$tableauIndex][$lastIndex];
                if (! $lastCard->faceUp) {
                    $tableaus[$tableauIndex][$lastIndex] = $lastCard->withFaceUp(true);
                }
            }
        }

        if ($to->isFoundation() && is_string($to->index)) {
            foreach ($cards as $card) {
                $foundations[$to->index][] = $card->withFaceUp(true);
            }
        } elseif ($to->isFoundation()) {
            $suit = $cards[0]->suit;
            foreach ($cards as $card) {
                $foundations[$suit][] = $card->withFaceUp(true);
            }
        } elseif ($to->isTableau() && $to->index !== null) {
            $tableauIndex = (int) $to->index;
            foreach ($cards as $card) {
                $tableaus[$tableauIndex][] = $card->withFaceUp(true);
            }
        }

        return new GameState(
            stock: $stock,
            waste: $waste,
            foundations: $foundations,
            tableaus: $tableaus,
        );
    }

    /**
     * Check if moving from a tableau will reveal a face-down card.
     *
     * @param  list<Card>  $cards
     */
    private function willFlipCard(GameState $state, MoveLocation $from, array $cards): bool
    {
        if (! $from->isTableau() || $from->index === null) {
            return false;
        }

        $tableauIndex = (int) $from->index;
        $tableau = $state->tableaus[$tableauIndex];
        $remainingCount = count($tableau) - count($cards);

        if ($remainingCount <= 0) {
            return false;
        }

        $cardToFlip = $tableau[$remainingCount - 1];

        return ! $cardToFlip->faceUp;
    }

    private function calculateScoreChange(MoveLocation $to, bool $willFlipCard): int
    {
        $score = 0;

        if ($to->isFoundation()) {
            $score += $this->scoreCalculator->calculateMoveToFoundation();
        } elseif ($to->isTableau()) {
            $score += $this->scoreCalculator->calculateMoveToTableau();
        }

        if ($willFlipCard) {
            $score += $this->scoreCalculator->calculateFlipCard();
        }

        return $score;
    }
}
