import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export function useCurrency() {
    const page = usePage();

    const symbol = computed(() => page.props.settings?.currency_symbol ?? '$');
    const position = computed(() => page.props.settings?.currency_position ?? 'before');

    function formatPrice(amount) {
        if (amount === null || amount === undefined) {
            return '-';
        }

        const formatted = Number(amount).toLocaleString('en-US');

        return position.value === 'before'
            ? `${symbol.value}${formatted}`
            : `${formatted} ${symbol.value}`;
    }

    return { symbol, position, formatPrice };
}

