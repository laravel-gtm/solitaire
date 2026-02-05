<script setup lang="ts">
import type { Card as CardType } from '@/types/solitaire';
import Card from './Card.vue';

withDefaults(
    defineProps<{
        cards: CardType[];
        highlighted?: boolean;
    }>(),
    {
        highlighted: false,
    },
);

const emit = defineEmits<{
    draw: [];
    reset: [];
}>();

function handleClick(hasCards: boolean) {
    if (hasCards) {
        emit('draw');
    } else {
        emit('reset');
    }
}
</script>

<template>
    <div class="relative h-[var(--card-height,100px)] w-[var(--card-width,70px)]">
        <div
            v-if="cards.length === 0"
            class="flex h-full w-full cursor-pointer items-center justify-center rounded-md border-2 border-dashed border-[#38bdf8]/30 bg-[#1e3a5f]/20 hover:border-[#38bdf8]/50 hover:bg-[#1e3a5f]/30 sm:rounded-lg"
            @click="handleClick(false)"
        >
            <svg class="size-6 stroke-[#38bdf8]/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
        </div>
        <div
            v-else
            class="cursor-pointer"
            :class="highlighted ? 'rounded-lg ring-2 ring-amber-400 ring-offset-2 animate-pulse' : ''"
            @click="handleClick(true)"
        >
            <div
                v-for="(_, index) in Math.min(cards.length, 3)"
                :key="`stock-back-${index}`"
                class="absolute"
                :style="{ top: `${index * 2}px`, left: `${index * 2}px` }"
            >
                <Card :card="null" />
            </div>
        </div>
    </div>
</template>
