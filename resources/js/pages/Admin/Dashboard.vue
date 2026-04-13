<script setup>
import { Head, Link } from '@inertiajs/vue3';
import admin from '@/routes/admin';
import cars from '@/routes/admin/cars';
import settings from '@/routes/admin/settings';

defineProps({
    stats: { type: Object, required: true },
});

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Admin',
                href: admin.dashboard(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <div class="space-y-6 p-6">
        <h1 class="text-3xl font-semibold">Admin Dashboard</h1>
        <div class="grid gap-4 md:grid-cols-5">
            <div class="rounded-xl border border-sidebar-border p-4">Total: {{ stats.total_cars }}</div>
            <div class="rounded-xl border border-sidebar-border p-4">Available: {{ stats.available_cars }}</div>
            <div class="rounded-xl border border-sidebar-border p-4">Featured: {{ stats.featured_cars }}</div>
            <div class="rounded-xl border border-sidebar-border p-4">Sold: {{ stats.sold_cars }}</div>
            <div class="rounded-xl border border-sidebar-border p-4">Views: {{ stats.total_views }}</div>
        </div>
        <div class="flex gap-3">
            <Link :href="cars.create()" class="rounded bg-primary px-4 py-2 text-primary-foreground">Add New Car</Link>
            <Link :href="settings.index()" class="rounded border border-sidebar-border px-4 py-2">Edit Settings</Link>
        </div>
    </div>
</template>

