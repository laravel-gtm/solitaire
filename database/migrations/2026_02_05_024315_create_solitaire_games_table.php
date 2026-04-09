<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('solitaire_games', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->enum('status', ['playing', 'won', 'abandoned'])->default('playing');
            $table->unsignedInteger('move_count')->default(0);
            $table->unsignedInteger('score')->default(0);
            $table->unsignedInteger('elapsed_seconds')->default(0);
            $table->json('state');
            $table->json('move_history')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }
};
