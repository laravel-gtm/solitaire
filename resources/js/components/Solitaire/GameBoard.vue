<script setup lang="ts">
import type { Card, GameState, Suit } from '@/types/solitaire';
import Foundations from './Foundations.vue';
import Stock from './Stock.vue';
import Tableaus from './Tableaus.vue';
import Waste from './Waste.vue';

withDefaults(
    defineProps<{
        state: GameState;
        highlightStock?: boolean;
        highlightWaste?: boolean;
        highlightedTableau?: number | null;
        highlightedCardIndex?: number | null;
    }>(),
    {
        highlightStock: false,
        highlightWaste: false,
        highlightedTableau: null,
        highlightedCardIndex: null,
    },
);

const emit = defineEmits<{
    draw: [];
    resetStock: [];
    moveToFoundation: [suit: Suit, event: DragEvent];
    moveToTableau: [tableauIndex: number, event: DragEvent];
    wasteDragStart: [event: DragEvent, card: Card];
    foundationDragStart: [suit: Suit, event: DragEvent, card: Card];
    tableauDragStart: [tableauIndex: number, event: DragEvent, cardIndex: number, cards: Card[]];
    wasteDoubleClick: [card: Card];
    tableauDoubleClick: [tableauIndex: number, cardIndex: number];
}>();
</script>

<template>
    <div class="flex flex-col gap-4 sm:gap-8">
        <div class="flex justify-between">
            <div class="flex gap-[var(--card-gap,12px)]">
                <Stock :cards="state.stock" :highlighted="highlightStock" @draw="emit('draw')" @reset="emit('resetStock')" />
                <Waste
                    :cards="state.waste"
                    :highlighted="highlightWaste"
                    @card-drag-start="(event, card) => emit('wasteDragStart', event, card)"
                    @card-dbl-click="(card) => emit('wasteDoubleClick', card)"
                />
            </div>
            <Foundations
                :foundations="state.foundations"
                @drop="(suit, event) => emit('moveToFoundation', suit, event)"
                @card-drag-start="(suit, event, card) => emit('foundationDragStart', suit, event, card)"
            />
        </div>
        <!-- Responsive min-height for tableau area -->
        <div class="min-h-[220px] sm:min-h-[280px] md:min-h-[364px]">
            <Tableaus
                :tableaus="state.tableaus"
                :highlighted-tableau="highlightedTableau"
                :highlighted-card-index="highlightedCardIndex"
                @drop="(index, event) => emit('moveToTableau', index, event)"
                @card-drag-start="(index, event, cardIndex, cards) => emit('tableauDragStart', index, event, cardIndex, cards)"
                @card-dbl-click="(index, cardIndex) => emit('tableauDoubleClick', index, cardIndex)"
            />
        </div>
    </div>
</template>
