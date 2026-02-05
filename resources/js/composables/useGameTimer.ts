import { onMounted, onUnmounted, ref } from 'vue';

export function useGameTimer(gameId: string, initialSeconds: number = 0) {
    const elapsedSeconds = ref(initialSeconds);
    let intervalId: ReturnType<typeof setInterval> | null = null;
    let syncIntervalId: ReturnType<typeof setInterval> | null = null;

    function start() {
        if (intervalId) return;

        intervalId = setInterval(() => {
            elapsedSeconds.value++;
        }, 1000);

        syncIntervalId = setInterval(() => {
            syncTime();
        }, 30000);
    }

    function stop() {
        if (intervalId) {
            clearInterval(intervalId);
            intervalId = null;
        }
        if (syncIntervalId) {
            clearInterval(syncIntervalId);
            syncIntervalId = null;
        }
    }

    async function syncTime() {
        try {
            await fetch(`/game/${gameId}/sync-time`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '',
                },
                body: JSON.stringify({ elapsed_seconds: elapsedSeconds.value }),
            });
        } catch {
            // Silently fail - time sync is not critical
        }
    }

    onMounted(() => {
        start();
    });

    onUnmounted(() => {
        stop();
        syncTime();
    });

    return {
        elapsedSeconds,
        start,
        stop,
        syncTime,
    };
}
