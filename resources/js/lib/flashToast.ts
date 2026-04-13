import { router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import type { FlashToast } from '@/types/ui';

export function initializeFlashToast(): void {
    router.on('flash', (event) => {
        const flash = (event as CustomEvent).detail?.flash;
        const data = flash?.toast as FlashToast | undefined;

        if (data?.message && data?.type) {
            toast[data.type](data.message);
            return;
        }

        if (flash?.success) {
            toast.success(String(flash.success));
        }

        if (flash?.error) {
            toast.error(String(flash.error));
        }
    });
}
