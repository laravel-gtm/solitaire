import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import DrawCardController from '@/actions/App/Http/Controllers/Solitaire/DrawCardController';
import MakeMoveController from '@/actions/App/Http/Controllers/Solitaire/MakeMoveController';
import ResetStockController from '@/actions/App/Http/Controllers/Solitaire/ResetStockController';
import { store } from '@/actions/App/Http/Controllers/Solitaire/SolitaireGameController';
import type { Card, Game, GameResponse, Hint, HintResponse, MoveLocation, MovePayload } from '@/types/solitaire';

function getCsrfToken(): string {
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
    return match ? decodeURIComponent(match[1]) : '';
}

async function requestJson<T>(url: string, method: string = 'POST', data?: unknown): Promise<T> {
    const response = await fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-XSRF-TOKEN': getCsrfToken(),
        },
        body: data !== undefined ? JSON.stringify(data) : undefined,
    });

    const json = await response.json();

    if (!response.ok) {
        throw new Error(json.error ?? json.message ?? `Request failed with status ${response.status}`);
    }

    return json as T;
}

export function useGameActions(gameId: string) {
    const loading = ref(false);
    const error = ref<string | null>(null);

    async function makeMove(from: MoveLocation, to: MoveLocation, cards: Card[]): Promise<Game | null> {
        loading.value = true;
        error.value = null;

        try {
            const payload: MovePayload = { from, to, cards };
            const response = await requestJson<GameResponse>(MakeMoveController.url(gameId), 'POST', payload);

            if (!response.success) {
                error.value = response.error ?? 'Move failed';
                return null;
            }

            return response.game ?? null;
        } catch (e) {
            error.value = e instanceof Error ? e.message : 'An error occurred';
            return null;
        } finally {
            loading.value = false;
        }
    }

    async function drawCard(): Promise<Game | null> {
        loading.value = true;
        error.value = null;

        try {
            const response = await requestJson<GameResponse>(DrawCardController.url(gameId));

            if (!response.success) {
                error.value = response.error ?? 'Draw failed';
                return null;
            }

            return response.game ?? null;
        } catch (e) {
            error.value = e instanceof Error ? e.message : 'An error occurred';
            return null;
        } finally {
            loading.value = false;
        }
    }

    async function resetStock(): Promise<Game | null> {
        loading.value = true;
        error.value = null;

        try {
            const response = await requestJson<GameResponse>(ResetStockController.url(gameId));

            if (!response.success) {
                error.value = response.error ?? 'Reset failed';
                return null;
            }

            return response.game ?? null;
        } catch (e) {
            error.value = e instanceof Error ? e.message : 'An error occurred';
            return null;
        } finally {
            loading.value = false;
        }
    }

    async function getHint(): Promise<{ hint: Hint | null; shouldDraw: boolean } | null> {
        try {
            const response = await requestJson<HintResponse>(`/game/${gameId}/hint`, 'GET');

            if (!response.success) {
                error.value = response.error ?? 'Failed to get hint';
                return null;
            }

            return { hint: response.hint, shouldDraw: response.shouldDraw };
        } catch (e) {
            error.value = e instanceof Error ? e.message : 'An error occurred';
            return null;
        }
    }

    function createNewGame(): void {
        router.post(store.url());
    }

    return {
        loading,
        error,
        makeMove,
        drawCard,
        resetStock,
        getHint,
        createNewGame,
    };
}
