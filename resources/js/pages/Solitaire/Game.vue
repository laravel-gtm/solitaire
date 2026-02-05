<script setup lang="ts">
import GameBoard from '@/components/Solitaire/GameBoard.vue';
import GameControls from '@/components/Solitaire/GameControls.vue';
import WinOverlay from '@/components/Solitaire/WinOverlay.vue';
import { useDragAndDrop } from '@/composables/useDragAndDrop';
import { useGameActions } from '@/composables/useGameActions';
import type { Card, Game, MoveLocation, Suit } from '@/types/solitaire';
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps<{
    game: Game;
}>();

const gameState = ref(props.game);
const { startDrag, getDragData, endDrag } = useDragAndDrop();
const { makeMove, drawCard, resetStock, undoMove, createNewGame, loading } = useGameActions(props.game.id);

const isWon = computed(() => gameState.value.status === 'won');
const canUndo = computed(() => gameState.value.canUndo ?? false);

async function handleDraw() {
    if (loading.value) return;
    const result = await drawCard();
    if (result) {
        gameState.value = result;
    }
}

async function handleResetStock() {
    if (loading.value) return;
    const result = await resetStock();
    if (result) {
        gameState.value = result;
    }
}

function handleWasteDragStart(event: DragEvent, card: Card) {
    startDrag(event, { type: 'waste' }, [card]);
}

function handleFoundationDragStart(suit: Suit, event: DragEvent, card: Card) {
    startDrag(event, { type: 'foundation', index: suit }, [card]);
}

function handleTableauDragStart(tableauIndex: number, event: DragEvent, _cardIndex: number, cards: Card[]) {
    startDrag(event, { type: 'tableau', index: tableauIndex }, cards);
}

async function handleMoveToFoundation(suit: Suit, event: DragEvent) {
    if (loading.value) return;

    const data = getDragData(event);
    endDrag();

    if (!data) return;

    const to: MoveLocation = { type: 'foundation', index: suit };
    const result = await makeMove(data.from, to, data.cards);
    if (result) {
        gameState.value = result;
    }
}

async function handleMoveToTableau(tableauIndex: number, event: DragEvent) {
    if (loading.value) return;

    const data = getDragData(event);
    endDrag();

    if (!data) return;

    const to: MoveLocation = { type: 'tableau', index: tableauIndex };
    const result = await makeMove(data.from, to, data.cards);
    if (result) {
        gameState.value = result;
    }
}

async function tryAutoMoveToFoundation(card: Card, from: MoveLocation) {
    if (loading.value) return;

    const to: MoveLocation = { type: 'foundation', index: card.suit };
    const result = await makeMove(from, to, [card]);
    if (result) {
        gameState.value = result;
    }
}

function handleWasteDoubleClick(card: Card) {
    tryAutoMoveToFoundation(card, { type: 'waste' });
}

function handleTableauDoubleClick(tableauIndex: number, cardIndex: number) {
    const tableau = gameState.value.state.tableaus[tableauIndex];
    if (cardIndex === tableau.length - 1) {
        const card = tableau[cardIndex];
        tryAutoMoveToFoundation(card, { type: 'tableau', index: tableauIndex });
    }
}

async function handleUndo() {
    if (loading.value) return;
    const result = await undoMove();
    if (result) {
        gameState.value = result;
    }
}

function handleNewGame() {
    createNewGame();
}
</script>

<template>
    <Head title="Klondike Solitaire">
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=jetbrains-mono:400,500,600,700" rel="stylesheet" />
    </Head>
    <div class="min-h-screen bg-[#0c1929] font-mono">
        <!-- Background grid pattern -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e3a5f20_1px,transparent_1px),linear-gradient(to_bottom,#1e3a5f20_1px,transparent_1px)] bg-[size:40px_40px]" />

        <div class="relative mx-auto max-w-4xl px-4 py-6">
            <div class="mb-6">
                <GameControls :move-count="gameState.moveCount" :score="gameState.score" :can-undo="canUndo" @new-game="handleNewGame" @undo="handleUndo" />
            </div>
            <div class="card-size relative rounded-xl border border-[#38bdf8]/40 bg-[#0a1420] p-3 shadow-[0_0_40px_rgba(56,189,248,0.15)] sm:p-6">
                <!-- Corner accents -->
                <div class="absolute -left-1 -top-1 h-6 w-6 border-l-2 border-t-2 border-[#38bdf8]" />
                <div class="absolute -right-1 -top-1 h-6 w-6 border-r-2 border-t-2 border-[#38bdf8]" />
                <div class="absolute -bottom-1 -left-1 h-6 w-6 border-b-2 border-l-2 border-[#38bdf8]" />
                <div class="absolute -bottom-1 -right-1 h-6 w-6 border-b-2 border-r-2 border-[#38bdf8]" />
                <GameBoard
                    :state="gameState.state"
                    @draw="handleDraw"
                    @reset-stock="handleResetStock"
                    @waste-drag-start="handleWasteDragStart"
                    @foundation-drag-start="handleFoundationDragStart"
                    @tableau-drag-start="handleTableauDragStart"
                    @move-to-foundation="handleMoveToFoundation"
                    @move-to-tableau="handleMoveToTableau"
                    @waste-double-click="handleWasteDoubleClick"
                    @tableau-double-click="handleTableauDoubleClick"
                />
            </div>
        </div>
    </div>
    <WinOverlay v-if="isWon" :move-count="gameState.moveCount" @new-game="handleNewGame" />
</template>

<style scoped>
* {
    font-family: 'JetBrains Mono', monospace;
}

/* Responsive card sizing */
:deep(.card-size) {
    --card-width: 48px;
    --card-height: 68px;
    --card-offset: 3px;
    --card-face-up-offset: 14px;
    --card-gap: 4px;
}

@media (min-width: 480px) {
    :deep(.card-size) {
        --card-width: 58px;
        --card-height: 82px;
        --card-offset: 3px;
        --card-face-up-offset: 16px;
        --card-gap: 6px;
    }
}

@media (min-width: 640px) {
    :deep(.card-size) {
        --card-width: 70px;
        --card-height: 100px;
        --card-offset: 4px;
        --card-face-up-offset: 20px;
        --card-gap: 12px;
    }
}
</style>
