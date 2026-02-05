<script setup lang="ts">
import { formatTime } from '@/types/solitaire';

defineProps<{
    moveCount: number;
    score: number;
    elapsedSeconds: number;
}>();

const emit = defineEmits<{
    newGame: [];
}>();
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-xs font-mono">
        <div class="relative mx-4 w-full max-w-md border border-[#38bdf8]/30 bg-[#0c1929] p-6 text-center shadow-2xl sm:p-8">
            <!-- Corner accents -->
            <div class="absolute -left-2 -top-2 size-4 border-l-2 border-t-2 border-[#38bdf8]" />
            <div class="absolute -bottom-2 -right-2 size-4 border-b-2 border-r-2 border-[#38bdf8]" />

            <div class="mb-4 text-6xl sm:text-5xl">
                <span class="text-red-500">&hearts;</span>
                <span class="text-slate-300">&spades;</span>
                <span class="text-red-500">&diams;</span>
                <span class="text-slate-300">&clubs;</span>
            </div>
            <div class="mb-2 flex items-center justify-center gap-2">
                <span class="size-2 animate-pulse rounded-full bg-emerald-500" />
                <span class="text-xs tracking-widest text-emerald-400 sm:text-[0.6875rem]">VICTORY ACHIEVED</span>
            </div>
            <h2 class="mb-4 text-balance text-4xl font-semibold tracking-tight text-white sm:mb-6 sm:text-3xl">YOU WIN!</h2>
            <div class="mb-6 grid grid-cols-3 gap-2 sm:gap-4">
                <div class="border border-[#1e3a5f] bg-[#1e3a5f]/30 p-2 sm:p-3">
                    <div class="text-[10px] font-semibold uppercase tracking-widest text-slate-500 sm:text-xs">Time</div>
                    <div class="text-lg font-bold tabular-nums text-white sm:text-xl">{{ formatTime(elapsedSeconds) }}</div>
                </div>
                <div class="border border-[#1e3a5f] bg-[#1e3a5f]/30 p-2 sm:p-3">
                    <div class="text-[10px] font-semibold uppercase tracking-widest text-slate-500 sm:text-xs">Score</div>
                    <div class="text-lg font-bold tabular-nums text-white sm:text-xl">{{ score }}</div>
                </div>
                <div class="border border-[#1e3a5f] bg-[#1e3a5f]/30 p-2 sm:p-3">
                    <div class="text-[10px] font-semibold uppercase tracking-widest text-slate-500 sm:text-xs">Moves</div>
                    <div class="text-lg font-bold tabular-nums text-white sm:text-xl">{{ moveCount }}</div>
                </div>
            </div>
            <button
                class="w-full bg-[#38bdf8] px-4 py-3 text-lg font-semibold tracking-wider text-[#0c1929] hover:bg-[#7dd3fc] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#38bdf8] sm:px-6 sm:py-2.5 sm:text-base"
                @click="emit('newGame')"
            >
                PLAY AGAIN
            </button>
        </div>
    </div>
</template>
