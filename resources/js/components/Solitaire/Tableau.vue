<script setup lang="ts">
import type { Card as CardType } from '@/types/solitaire';
import { computed } from 'vue';
import CardStack from './CardStack.vue';

const props = withDefaults(
    defineProps<{
        index: number;
        cards: CardType[];
        highlightedCardIndex?: number | null;
    }>(),
    {
        highlightedCardIndex: null,
    },
);

const emit = defineEmits<{
    drop: [event: DragEvent];
    cardDragStart: [event: DragEvent, cardIndex: number, cards: CardType[]];
    cardDblClick: [cardIndex: number];
}>();

const firstFaceUpIndex = computed(() => {
    const idx = props.cards.findIndex((card) => card.faceUp);
    return idx >= 0 ? idx : props.cards.length;
});

function handleDragOver(event: DragEvent) {
    event.preventDefault();
}

function handleDrop(event: DragEvent) {
    event.preventDefault();
    emit('drop', event);
}
</script>

<template>
    <div
        class="relative w-[var(--card-width,70px)]"
        @dragover="handleDragOver"
        @drop="handleDrop"
    >
        <div
            v-if="cards.length === 0"
            class="flex h-[var(--card-height,100px)] w-full items-center justify-center rounded-md border-2 border-dashed border-[#38bdf8]/20 bg-[#1e3a5f]/10 sm:rounded-lg"
        >
            <span class="text-lg font-bold text-[#38bdf8]/30 sm:text-2xl">K</span>
        </div>
        <CardStack
            v-else
            :cards="cards"
            :location="{ type: 'tableau', index }"
            :draggable-from="firstFaceUpIndex"
            :highlighted-card-index="highlightedCardIndex"
            @card-drag-start="(event, cardIndex, cards) => emit('cardDragStart', event, cardIndex, cards)"
            @card-dbl-click="(cardIndex) => emit('cardDblClick', cardIndex)"
        />
    </div>
</template>
