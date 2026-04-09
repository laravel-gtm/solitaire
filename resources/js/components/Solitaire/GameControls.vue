<script setup lang="ts">
defineProps<{
    moveCount: number;
    score: number;
}>();

const emit = defineEmits<{
    newGame: [];
}>();
</script>

<template>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center justify-between sm:justify-start sm:gap-4">
            <div class="flex items-center gap-2">
                <span class="text-2xl sm:text-xl">
                    <span class="text-red-500">&hearts;</span>
                    <span class="text-[#38bdf8]">&spades;</span>
                </span>
                <h1 class="text-xl font-semibold tracking-wider text-white sm:text-lg">KLONDIKE</h1>
            </div>
            <button
                class="flex items-center gap-1.5 bg-[#38bdf8] py-2 pl-2.5 pr-3 text-sm font-semibold tracking-wider text-[#0c1929] hover:bg-[#7dd3fc] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#38bdf8] sm:hidden"
                @click="emit('newGame')"
            >
                <svg class="size-4 shrink-0" fill="none" viewBox="0 0 16 16" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.5 2v3.5h3.5M12.5 14v-3.5H9M3.5 5.5A5 5 0 0112 4.5M12.5 10.5A5 5 0 014 11.5" />
                </svg>
                NEW
            </button>
        </div>
        <div class="flex items-center justify-center gap-4 sm:gap-6">
            <div class="flex items-center gap-1.5 sm:gap-2">
                <span class="text-xs font-semibold uppercase tracking-widest text-slate-500 sm:text-[0.6875rem]">Moves</span>
                <span class="text-lg font-semibold tabular-nums text-white sm:text-base">{{ moveCount }}</span>
            </div>
            <div class="flex items-center gap-1.5 sm:gap-2">
                <span class="text-xs font-semibold uppercase tracking-widest text-slate-500 sm:text-[0.6875rem]">Score</span>
                <span class="text-lg font-semibold tabular-nums text-white sm:text-base">{{ score }}</span>
            </div>
            <button
                class="hidden items-center gap-2 bg-[#38bdf8] py-2 pl-3 pr-4 text-sm font-semibold tracking-wider text-[#0c1929] hover:bg-[#7dd3fc] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#38bdf8] sm:flex"
                @click="emit('newGame')"
            >
                <svg class="size-4 shrink-0" fill="none" viewBox="0 0 16 16" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.5 2v3.5h3.5M12.5 14v-3.5H9M3.5 5.5A5 5 0 0112 4.5M12.5 10.5A5 5 0 014 11.5" />
                </svg>
                NEW GAME
            </button>
        </div>
    </div>
</template>
