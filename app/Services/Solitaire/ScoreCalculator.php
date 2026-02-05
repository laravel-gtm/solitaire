<?php

namespace App\Services\Solitaire;

class ScoreCalculator
{
    public const int POINTS_TO_FOUNDATION = 10;

    public const int POINTS_TO_TABLEAU = 5;

    public const int POINTS_FLIP_CARD = 5;

    public const int POINTS_RECYCLE_WASTE = -100;

    public function calculateMoveToFoundation(): int
    {
        return self::POINTS_TO_FOUNDATION;
    }

    public function calculateMoveToTableau(): int
    {
        return self::POINTS_TO_TABLEAU;
    }

    public function calculateFlipCard(): int
    {
        return self::POINTS_FLIP_CARD;
    }

    public function calculateRecycleWaste(): int
    {
        return self::POINTS_RECYCLE_WASTE;
    }
}
