import type { Card, Game, GameResponse, Hint, HintResponse, MoveLocation, MovePayload } from '@/types/solitaire';
import MakeMoveController from '@/actions/App/Http/Controllers/Solitaire/MakeMoveController';
import DrawCardController from '@/actions/App/Http/Controllers/Solitaire/DrawCardController';
import ResetStockController from '@/actions/App/Http/Controllers/Solitaire/ResetStockController';
import { store } from '@/actions/App/Http/Controllers/Solitaire/SolitaireGameController';
import { useHttp, router } from '@inertiajs/vue3';
import { ref } from 'vue';

export function useGameActions(gameId: string) {
    const http = useHttp();
    const loading = ref(false);
    const error = ref<string | null>(null);

    async function makeMove(from: MoveLocation, to: MoveLocation, cards: Card[]): Promise<Game | null> {
        loading.value = true;
        error.value = null;

        try {
            const payload: MovePayload = { from, to, cards };
            const { data } = await http.post(MakeMoveController.url(gameId), payload);
            const response = data as GameResponse;

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
            const { data } = await http.post(DrawCardController.url(gameId));
            const response = data as GameResponse;

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
            const { data } = await http.post(ResetStockController.url(gameId));
            const response = data as GameResponse;

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
            const { data } = await http.get(`/game/${gameId}/hint`);
            const response = data as HintResponse;

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
