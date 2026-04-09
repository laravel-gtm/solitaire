<script setup lang="ts">
import type { Card as CardType, MoveLocation } from '@/types/solitaire';
import Card from './Card.vue';

interface Props {
    cards: CardType[];
    location: MoveLocation;
    draggableFrom?: number;
}

const { cards, location, draggableFrom = -1 } = defineProps<Props>();

const emit = defineEmits<{
    cardDragStart: [event: DragEvent, cardIndex: number, cards: CardType[]];
    cardClick: [cardIndex: number];
    cardDblClick: [cardIndex: number];
}>();

function getOffset(index: number): string {
    if (index === 0) return '0px';
    const prevCard = cards[index - 1];
    return prevCard?.faceUp ? 'var(--card-face-up-offset, 20px)' : 'var(--card-offset, 4px)';
}

function getTopPosition(index: number): string {
    // Calculate cumulative offset using CSS calc
    let calc = '0px';
    for (let i = 1; i <= index; i++) {
        const prevCard = cards[i - 1];
        const offset = prevCard?.faceUp ? 'var(--card-face-up-offset, 20px)' : 'var(--card-offset, 4px)';
        calc = `calc(${calc} + ${offset})`;
    }
    return calc;
}

function isDraggable(index: number): boolean {
    if (draggableFrom < 0) return false;
    return index >= draggableFrom && cards[index]?.faceUp;
}

function handleDragStart(event: DragEvent, cardIndex: number) {
    const cards = cards.slice(cardIndex);
    emit('cardDragStart', event, cardIndex, cards);
}
</script>

<template>
    <div class="relative">
        <div
            v-for="(card, index) in cards"
            :key="`${card.suit}-${card.rank}-${index}`"
            class="absolute left-0"
            :style="{ top: getTopPosition(index) }"
        >
            <Card
                :card="card"
                :draggable="isDraggable(index)"
                @dragstart="handleDragStart($event, index)"
                @click="emit('cardClick', index)"
                @dblclick="emit('cardDblClick', index)"
            />
        </div>
    </div>
</template>
