<script setup lang="ts">
import type { Card as CardType } from '@/types/solitaire';
import Tableau from './Tableau.vue';

withDefaults(
    defineProps<{
        tableaus: CardType[][];
        highlightedTableau?: number | null;
        highlightedCardIndex?: number | null;
    }>(),
    {
        highlightedTableau: null,
        highlightedCardIndex: null,
    },
);

const emit = defineEmits<{
    drop: [tableauIndex: number, event: DragEvent];
    cardDragStart: [tableauIndex: number, event: DragEvent, cardIndex: number, cards: CardType[]];
    cardDblClick: [tableauIndex: number, cardIndex: number];
}>();
</script>

<template>
    <div class="flex justify-between gap-[var(--card-gap,12px)] sm:justify-start">
        <Tableau
            v-for="(tableau, index) in tableaus"
            :key="`tableau-${index}`"
            :index="index"
            :cards="tableau"
            :highlighted-card-index="highlightedTableau === index ? highlightedCardIndex : null"
            @drop="(event) => emit('drop', index, event)"
            @card-drag-start="(event, cardIndex, cards) => emit('cardDragStart', index, event, cardIndex, cards)"
            @card-dbl-click="(cardIndex) => emit('cardDblClick', index, cardIndex)"
        />
    </div>
</template>
