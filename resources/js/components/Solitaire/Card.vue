<script setup lang="ts">
import type { Card } from '@/types/solitaire';
import { getRankDisplay, getSuitSymbol, isRedSuit } from '@/types/solitaire';
import { computed } from 'vue';

interface Props {
    card?: Card | null;
    faceUp?: boolean;
    draggable?: boolean;
    selected?: boolean;
    highlighted?: boolean;
}

const { card = null, faceUp = false, draggable = false, selected = false, highlighted = false } = defineProps<Props>();

const emit = defineEmits<{
    dragstart: [event: DragEvent];
    click: [];
    dblclick: [];
}>();

const isRed = computed(() => card && isRedSuit(card.suit));
const rankDisplay = computed(() => (card ? getRankDisplay(card.rank) : ''));
const suitSymbol = computed(() => (card ? getSuitSymbol(card.suit) : ''));
const showFace = computed(() => card && (card.faceUp || faceUp));

function handleDragStart(event: DragEvent) {
    if (draggable && showFace.value) {
        emit('dragstart', event);
    }
}
</script>

<template>
    <div
        class="card flex h-[var(--card-height,100px)] w-[var(--card-width,70px)] select-none flex-col rounded-md border shadow-md sm:rounded-lg sm:shadow-lg"
        :class="[
            showFace
                ? 'cursor-pointer border-slate-300 bg-white'
                : 'cursor-default border-[#38bdf8]/50 bg-linear-to-br from-[#1e3a5f] to-[#0c1929]',
            selected ? 'ring-2 ring-[#38bdf8] ring-offset-2 ring-offset-[#0c1929]' : '',
            highlighted ? 'ring-4 ring-amber-400 ring-offset-2 ring-offset-[#0c1929] animate-pulse shadow-[0_0_20px_rgba(251,191,36,0.7)] scale-105 z-10' : '',
            draggable && showFace ? 'cursor-grab active:cursor-grabbing' : '',
        ]"
        :draggable="draggable && showFace"
        @dragstart="handleDragStart"
        @click="emit('click')"
        @dblclick="emit('dblclick')"
    >
        <template v-if="showFace && card">
            <div class="flex flex-1 flex-col p-1 sm:p-1.5" :class="isRed ? 'text-red-600' : 'text-slate-800'">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold leading-none sm:text-sm">{{ rankDisplay }}</span>
                    <span class="text-sm leading-none sm:text-base">{{ suitSymbol }}</span>
                </div>
                <div class="flex flex-1 items-center justify-center">
                    <span class="text-xl sm:text-3xl">{{ suitSymbol }}</span>
                </div>
                <div class="flex rotate-180 items-center justify-between">
                    <span class="text-xs font-bold leading-none sm:text-sm">{{ rankDisplay }}</span>
                    <span class="text-sm leading-none sm:text-base">{{ suitSymbol }}</span>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="flex h-full items-center justify-center">
                <div class="flex flex-col items-center gap-0.5 sm:gap-1">
                    <div class="h-0.5 w-6 rounded-sm bg-[#38bdf8]/30 sm:h-1 sm:w-10" />
                    <div class="h-0.5 w-5 rounded-sm bg-[#38bdf8]/30 sm:h-1 sm:w-8" />
                    <div class="h-0.5 w-6 rounded-sm bg-[#38bdf8]/30 sm:h-1 sm:w-10" />
                </div>
            </div>
        </template>
    </div>
</template>
