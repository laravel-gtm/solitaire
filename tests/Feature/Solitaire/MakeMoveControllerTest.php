<?php

use App\Actions\Solitaire\CreateGameAction;
use App\Actions\Solitaire\DrawCardAction;
use App\Models\SolitaireGame;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('valid move returns success', function () {
    $game = app(CreateGameAction::class)->execute();
    $state = $game->state;

    // Find a face-up card on a tableau to move
    foreach ($state->tableaus as $i => $tableau) {
        if (empty($tableau)) {
            continue;
        }
        $top = $tableau[count($tableau) - 1];
        if (! $top->faceUp) {
            continue;
        }
        foreach ($state->tableaus as $j => $target) {
            if ($i === $j) {
                continue;
            }
            if (empty($target) && $top->rank === 13) {
                $response = $this->postJson("/game/{$game->id}/move", [
                    'from' => ['type' => 'tableau', 'index' => $i],
                    'to' => ['type' => 'tableau', 'index' => $j],
                    'cards' => [['suit' => $top->suit, 'rank' => $top->rank, 'faceUp' => true]],
                ]);
                $response->assertOk()->assertJson(['success' => true]);

                return;
            }
            if (! empty($target)) {
                $targetTop = $target[count($target) - 1];
                if ($targetTop->faceUp && $top->rank === $targetTop->rank - 1 && $top->isOppositeColor($targetTop)) {
                    $response = $this->postJson("/game/{$game->id}/move", [
                        'from' => ['type' => 'tableau', 'index' => $i],
                        'to' => ['type' => 'tableau', 'index' => $j],
                        'cards' => [['suit' => $top->suit, 'rank' => $top->rank, 'faceUp' => true]],
                    ]);
                    $response->assertOk()->assertJson(['success' => true]);

                    return;
                }
            }
        }
    }

    // If no tableau-to-tableau move, draw and try waste-to-foundation
    $game = app(DrawCardAction::class)->execute($game);
    $waste = $game->state->waste;
    $wasteTop = $waste[count($waste) - 1];

    if ($wasteTop->rank === 1) {
        $response = $this->postJson("/game/{$game->id}/move", [
            'from' => ['type' => 'waste'],
            'to' => ['type' => 'foundation', 'index' => $wasteTop->suit],
            'cards' => [['suit' => $wasteTop->suit, 'rank' => $wasteTop->rank, 'faceUp' => true]],
        ]);
        $response->assertOk()->assertJson(['success' => true]);

        return;
    }

    $this->markTestSkipped('No valid move found in random deal');
});

test('invalid move returns 422', function () {
    $game = app(CreateGameAction::class)->execute();

    // Try to move a non-king to an empty space (invalid)
    $response = $this->postJson("/game/{$game->id}/move", [
        'from' => ['type' => 'tableau', 'index' => 0],
        'to' => ['type' => 'foundation', 'index' => 'hearts'],
        'cards' => [['suit' => 'hearts', 'rank' => 5, 'faceUp' => true]],
    ]);

    $response->assertStatus(422);
});

test('validation fails with missing fields', function () {
    $game = app(CreateGameAction::class)->execute();

    $response = $this->postJson("/game/{$game->id}/move", []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['from', 'to', 'cards']);
});

test('validation fails with invalid card suit', function () {
    $game = app(CreateGameAction::class)->execute();

    $response = $this->postJson("/game/{$game->id}/move", [
        'from' => ['type' => 'tableau', 'index' => 0],
        'to' => ['type' => 'tableau', 'index' => 1],
        'cards' => [['suit' => 'invalid', 'rank' => 5, 'faceUp' => true]],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['cards.0.suit']);
});
