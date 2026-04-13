<script setup>
import { Link } from '@inertiajs/vue3';
import { toUrl } from '@/lib/utils';

defineProps({
    links: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <nav v-if="links.length > 3" class="mt-8 flex flex-wrap items-center gap-2">
        <template v-for="(link, index) in links" :key="index">
            <span
                v-if="!link.url"
                class="rounded-lg border border-white/10 px-3 py-2 text-sm text-[#6B7280]"
                v-html="link.label"
            />
            <Link
                v-else
                :href="toUrl(link.url)"
                class="rounded-lg border px-3 py-2 text-sm transition-all duration-300"
                :class="link.active ? 'border-brand-400 bg-brand-400 text-black' : 'border-white/10 text-[#9CA3AF] hover:border-brand-400/40 hover:text-brand-400'"
                v-html="link.label"
            />
        </template>
    </nav>
</template>

