<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const siteName = computed(() => page.props.settings?.site_name ?? 'AutoNest');
const siteTagline = computed(() => page.props.settings?.site_tagline ?? 'Drive Your Dream');
const siteLogoUrl = computed(() => page.props.settings?.site_logo_url ?? '/images/autonest-logo.svg');
</script>

<template>
    <img :src="siteLogoUrl" :alt="siteName" class="h-10 w-auto rounded-md object-contain" />
    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate leading-tight font-semibold">{{ siteName }}</span>
        <span class="truncate text-xs text-muted-foreground">{{ siteTagline }}</span>
    </div>
</template>
