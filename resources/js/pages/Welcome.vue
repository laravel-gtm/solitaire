<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { store } from '@/actions/App/Http/Controllers/Solitaire/SolitaireGameController';

function startGame() {
    router.post(store.url());
}
</script>

<template>
    <Head title="Klondike Solitaire">
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=jetbrains-mono:400,500,600,700" rel="stylesheet" />
    </Head>

    <div class="min-h-dvh bg-[#0c1929] font-mono text-white">
        <!-- Header -->
        <header class="border-b border-[#1e3a5f]/50 bg-[#0c1929]/80 backdrop-blur-sm">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 sm:py-4">
                <div class="flex items-center gap-2 sm:gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl sm:text-xl">
                            <span class="text-red-500">&hearts;</span>
                            <span class="text-[#38bdf8]">&spades;</span>
                        </span>
                        <span class="text-lg font-semibold tracking-wider sm:text-base">KLONDIKE</span>
                    </div>
                    <span class="hidden text-xs tracking-widest text-slate-500 sm:inline">// DECK.READY</span>
                </div>
                <div class="flex items-center gap-4 text-xs tracking-widest text-slate-400 sm:gap-8 sm:text-[0.6875rem]">
                    <a href="#rules" class="hidden hover:text-white sm:inline">RULES</a>
                    <a href="#features" class="hidden hover:text-white sm:inline">FEATURES</a>
                    <span class="rounded-sm border border-[#38bdf8]/30 bg-[#38bdf8]/10 px-2 py-1 text-[#38bdf8] sm:px-3">52 CARDS</span>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="relative overflow-hidden">
            <!-- Background grid pattern -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e3a5f20_1px,transparent_1px),linear-gradient(to_bottom,#1e3a5f20_1px,transparent_1px)] bg-[size:40px_40px]" />

            <!-- Vertical text on left -->
            <div class="absolute left-0 top-1/2 hidden -translate-y-1/2 -rotate-90 transform text-[5rem] font-semibold tracking-widest text-[#1e3a5f]/30 lg:block">
                PATIENCE
            </div>

            <div class="relative mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-16">
                <div class="grid gap-8 sm:gap-12 lg:grid-cols-2">
                    <!-- Left content -->
                    <div class="flex flex-col justify-center gap-4 text-center sm:gap-6 lg:text-left">
                        <!-- Status badge -->
                        <div class="flex items-center justify-center gap-2 lg:justify-start">
                            <span class="size-2 animate-pulse rounded-full bg-emerald-500" />
                            <span class="text-xs tracking-widest text-emerald-400 sm:text-[0.6875rem]">SHUFFLED & READY</span>
                        </div>

                        <!-- Main headline -->
                        <h1 class="text-balance">
                            <span class="block text-6xl font-semibold tracking-tighter text-white sm:text-5xl lg:text-[4.5rem]">COOL</span>
                            <span class="block text-6xl font-semibold tracking-tighter text-[#38bdf8] sm:text-5xl lg:text-[4.5rem]">SOLITAIRE</span>
                        </h1>

                        <!-- Description -->
                        <div class="mx-auto border-l-2 border-slate-600 pl-4 text-left lg:mx-0">
                            <p class="max-w-md text-pretty text-base/7 text-slate-400 sm:text-sm/6">
                                The timeless single-player card game. Build four foundation piles from Ace to King.
                                Stack cards in descending order, alternating colors. No downloads, no accounts.
                            </p>
                        </div>

                        <!-- CTA Button -->
                        <div class="flex justify-center gap-4 lg:justify-start">
                            <button
                                @click="startGame"
                                class="group flex items-center gap-2 bg-[#38bdf8] py-3 pl-6 pr-5 text-base font-semibold tracking-wider text-[#0c1929] hover:bg-[#7dd3fc] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#38bdf8] sm:gap-3 sm:py-2.5 sm:pl-5 sm:pr-4 sm:text-sm"
                            >
                                DEAL CARDS
                                <svg class="size-5 shrink-0 group-hover:translate-x-1 sm:size-4" fill="none" viewBox="0 0 20 20" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.25 3.75L17.5 10m0 0l-6.25 6.25M17.5 10H2.5" />
                                </svg>
                            </button>
                        </div>

                        <!-- Quick stats -->
                        <div class="flex justify-center gap-4 text-xs tracking-widest text-slate-500 sm:gap-8 sm:text-[0.6875rem] lg:justify-start">
                            <div>
                                <span class="text-2xl font-semibold tabular-nums text-white sm:text-xl">7</span>
                                <span class="ml-1 sm:ml-2">TABLEAUS</span>
                            </div>
                            <div>
                                <span class="text-2xl font-semibold tabular-nums text-white sm:text-xl">4</span>
                                <span class="ml-1 sm:ml-2">FOUNDATIONS</span>
                            </div>
                            <div>
                                <span class="text-2xl font-semibold tabular-nums text-white sm:text-xl">1</span>
                                <span class="ml-1 sm:ml-2">DRAW</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right panel - Card visualization -->
                    <div class="hidden items-center justify-center lg:flex">
                        <div class="relative w-full max-w-md">
                            <!-- Card display panel -->
                            <div class="border border-[#38bdf8]/30 bg-[#0c1929]/80 p-8 backdrop-blur-sm">
                                <!-- Header -->
                                <div class="mb-6 flex items-center justify-between">
                                    <span class="text-xs tracking-widest text-slate-400">FOUNDATION</span>
                                    <span class="text-xs tracking-widest text-emerald-400">4 SUITS</span>
                                </div>

                                <!-- Four suit icons representing foundations -->
                                <div class="mb-8 flex justify-center gap-4">
                                    <div class="flex h-24 w-16 items-center justify-center rounded-lg border-2 border-dashed border-[#38bdf8]/30 bg-[#1e3a5f]/20">
                                        <span class="text-3xl text-red-500/50">&hearts;</span>
                                    </div>
                                    <div class="flex h-24 w-16 items-center justify-center rounded-lg border-2 border-dashed border-[#38bdf8]/30 bg-[#1e3a5f]/20">
                                        <span class="text-3xl text-red-500/50">&diams;</span>
                                    </div>
                                    <div class="flex h-24 w-16 items-center justify-center rounded-lg border-2 border-dashed border-[#38bdf8]/30 bg-[#1e3a5f]/20">
                                        <span class="text-3xl text-slate-400/50">&clubs;</span>
                                    </div>
                                    <div class="flex h-24 w-16 items-center justify-center rounded-lg border-2 border-dashed border-[#38bdf8]/30 bg-[#1e3a5f]/20">
                                        <span class="text-3xl text-slate-400/50">&spades;</span>
                                    </div>
                                </div>

                                <!-- Card stack visualization -->
                                <div class="mb-6 flex justify-center">
                                    <div class="relative">
                                        <!-- Stacked cards effect -->
                                        <div class="absolute -right-3 top-3 h-32 w-20 rounded-lg border border-[#38bdf8]/10 bg-[#1e3a5f]/20" />
                                        <div class="absolute -right-1.5 top-1.5 h-32 w-20 rounded-lg border border-[#38bdf8]/20 bg-[#1e3a5f]/30" />
                                        <!-- Top card -->
                                        <div class="relative h-32 w-20 rounded-lg border-2 border-[#38bdf8]/50 bg-linear-to-br from-white to-slate-100">
                                            <div class="flex h-full flex-col justify-between p-2">
                                                <div class="text-left">
                                                    <span class="text-lg font-semibold text-slate-800">A</span>
                                                    <span class="ml-1 text-lg text-red-500">&hearts;</span>
                                                </div>
                                                <div class="flex items-center justify-center">
                                                    <span class="text-4xl text-red-500">&hearts;</span>
                                                </div>
                                                <div class="rotate-180 text-left">
                                                    <span class="text-lg font-semibold text-slate-800">A</span>
                                                    <span class="ml-1 text-lg text-red-500">&hearts;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Objective text -->
                                <p class="text-center text-xs tracking-widest text-slate-500">
                                    MOVE ALL CARDS TO FOUNDATIONS
                                </p>
                            </div>

                            <!-- Corner accents -->
                            <div class="absolute -left-2 -top-2 size-4 border-l-2 border-t-2 border-[#38bdf8]" />
                            <div class="absolute -bottom-2 -right-2 size-4 border-b-2 border-r-2 border-[#38bdf8]" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature badges bar -->
            <div id="features" class="border-y border-[#1e3a5f] bg-[#1e3a5f]/20 py-3 sm:py-4">
                <div class="mx-auto grid max-w-7xl grid-cols-2 gap-3 px-4 sm:flex sm:items-center sm:justify-between sm:gap-0 sm:px-6">
                    <div class="flex items-center justify-center gap-2 text-[#38bdf8] sm:gap-3">
                        <span class="text-lg sm:text-base">&spades;</span>
                        <span class="text-sm tracking-widest sm:text-xs">DRAG & DROP</span>
                    </div>
                    <div class="hidden h-4 w-px bg-[#1e3a5f]/50 sm:block" />
                    <div class="flex items-center justify-center gap-2 text-[#38bdf8] sm:gap-3">
                        <span class="text-lg text-red-500 sm:text-base">&hearts;</span>
                        <span class="text-sm tracking-widest sm:text-xs">AUTO SAVE</span>
                    </div>
                    <div class="hidden h-4 w-px bg-[#1e3a5f]/50 sm:block" />
                    <div class="flex items-center justify-center gap-2 text-[#38bdf8] sm:gap-3">
                        <span class="text-lg text-red-500 sm:text-base">&diams;</span>
                        <span class="text-sm tracking-widest sm:text-xs">MOVE COUNTER</span>
                    </div>
                    <div class="hidden h-4 w-px bg-[#1e3a5f]/50 sm:block" />
                    <div class="flex items-center justify-center gap-2 text-[#38bdf8] sm:gap-3">
                        <span class="text-lg sm:text-base">&clubs;</span>
                        <span class="text-sm tracking-widest sm:text-xs">INSTANT PLAY</span>
                    </div>
                </div>
            </div>

            <!-- Rules section -->
            <div id="rules" class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-16">
                <div class="grid gap-6 sm:gap-8 md:grid-cols-3">
                    <!-- Rule 1 -->
                    <div class="border-l-2 border-[#38bdf8]/30 pl-4 sm:pl-6">
                        <span class="text-xs tracking-widest text-[#38bdf8] sm:text-[0.6875rem]">RULE-01 // TABLEAU</span>
                        <h3 class="mb-2 mt-1 text-balance text-2xl font-semibold tracking-tight sm:mb-4 sm:mt-2 sm:text-xl">BUILD DOWN</h3>
                        <p class="text-pretty text-base/7 text-slate-400 sm:text-sm/6">
                            Stack cards in descending order, alternating between red and black suits.
                            King through Ace.
                        </p>
                    </div>

                    <!-- Rule 2 -->
                    <div class="border-l-2 border-[#38bdf8]/30 pl-4 sm:pl-6">
                        <span class="text-xs tracking-widest text-[#38bdf8] sm:text-[0.6875rem]">RULE-02 // FOUNDATION</span>
                        <h3 class="mb-2 mt-1 text-balance text-2xl font-semibold tracking-tight sm:mb-4 sm:mt-2 sm:text-xl">BUILD UP</h3>
                        <p class="text-pretty text-base/7 text-slate-400 sm:text-sm/6">
                            Move cards to foundations by suit, ascending from Ace to King.
                            Complete all four to win.
                        </p>
                    </div>

                    <!-- Rule 3 -->
                    <div class="border-l-2 border-[#38bdf8]/30 pl-4 sm:pl-6">
                        <span class="text-xs tracking-widest text-[#38bdf8] sm:text-[0.6875rem]">RULE-03 // STOCK</span>
                        <h3 class="mb-2 mt-1 text-balance text-2xl font-semibold tracking-tight sm:mb-4 sm:mt-2 sm:text-xl">DRAW ONE</h3>
                        <p class="text-pretty text-base/7 text-slate-400 sm:text-sm/6">
                            Click the stock pile to draw cards. When empty, click to recycle
                            the waste pile back to stock.
                        </p>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-[#1e3a5f]/50 py-6">
            <div class="mx-auto max-w-7xl px-6">
                <div class="flex flex-col items-center justify-between gap-4 text-xs tracking-widest text-slate-500 sm:flex-row">
                    <span class="text-center sm:text-left">BUILT WITH LARAVEL + INERTIA + VUE</span>
                    <span class="text-center text-slate-600">
                        Design inspired by
                        <a
                            href="https://x.com/rm_codes"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-[#38bdf8] hover:text-[#7dd3fc]"
                        >@rm_codes</a>
                    </span>
                    <span class="flex items-center gap-2">
                        <span class="text-red-500">&hearts;</span>
                        <span class="text-red-500">&diams;</span>
                        <span>&clubs;</span>
                        <span>&spades;</span>
                    </span>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
* {
    font-family: 'JetBrains Mono', monospace;
}
</style>
