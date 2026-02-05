import type { Card, Game, GameResponse, MoveLocation, MovePayload } from '@/types/solitaire';
import { ref } from 'vue';

export function useGameActions(gameId: string) {
    const loading = ref(false);
    const error = ref<string | null>(null);

    async function makeMove(from: MoveLocation, to: MoveLocation, cards: Card[]): Promise<Game | null> {
        loading.value = true;
        error.value = null;

        try {
            const payload: MovePayload = { from, to, cards };
            const response = await fetch(`/game/${gameId}/move`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '',
                },
                body: JSON.stringify(payload),
            });

            const data: GameResponse = await response.json();

            if (!data.success) {
                error.value = data.error ?? 'Move failed';
                return null;
            }

            return data.game ?? null;
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
            const response = await fetch(`/game/${gameId}/draw`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '',
                },
            });

            const data: GameResponse = await response.json();

            if (!data.success) {
                error.value = data.error ?? 'Draw failed';
                return null;
            }

            return data.game ?? null;
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
            const response = await fetch(`/game/${gameId}/reset-stock`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '',
                },
            });

            const data: GameResponse = await response.json();

            if (!data.success) {
                error.value = data.error ?? 'Reset failed';
                return null;
            }

            return data.game ?? null;
        } catch (e) {
            error.value = e instanceof Error ? e.message : 'An error occurred';
            return null;
        } finally {
            loading.value = false;
        }
    }

    async function undoMove(): Promise<Game | null> {
        loading.value = true;
        error.value = null;

        try {
            const response = await fetch(`/game/${gameId}/undo`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '',
                },
            });

            const data: GameResponse = await response.json();

            if (!data.success) {
                error.value = data.error ?? 'Undo failed';
                return null;
            }

            return data.game ?? null;
        } catch (e) {
            error.value = e instanceof Error ? e.message : 'An error occurred';
            return null;
        } finally {
            loading.value = false;
        }
    }

    async function createNewGame(): Promise<void> {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/game';

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '';
        form.appendChild(csrfInput);

        document.body.appendChild(form);
        form.submit();
    }

    return {
        loading,
        error,
        makeMove,
        drawCard,
        resetStock,
        undoMove,
        createNewGame,
    };
}
