<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Car, Eye, Gauge, Layers3, Sparkles, TrendingUp } from 'lucide-vue-next';
import admin from '@/routes/admin';
import cars from '@/routes/admin/cars';
import settings from '@/routes/admin/settings';
import { useCurrency } from '@/composables/useCurrency';

const props = defineProps({
    stats: { type: Object, required: true },
    topMakes: { type: Array, default: () => [] },
    bodyTypeMix: { type: Array, default: () => [] },
    topViewedCars: { type: Array, default: () => [] },
    recentListings: { type: Array, default: () => [] },
});

const { formatPrice } = useCurrency();

const formatNumber = (value) => new Intl.NumberFormat('en-US').format(Number(value ?? 0));

const statCards = [
    {
        title: 'Total Inventory',
        value: formatNumber(props.stats.total_cars),
        icon: Car,
        accent: 'from-sky-500/20 to-sky-500/5',
    },
    {
        title: 'Available Cars',
        value: formatNumber(props.stats.available_cars),
        icon: Layers3,
        accent: 'from-emerald-500/20 to-emerald-500/5',
    },
    {
        title: 'Sold Cars',
        value: formatNumber(props.stats.sold_cars),
        icon: TrendingUp,
        accent: 'from-amber-500/20 to-amber-500/5',
    },
    {
        title: 'Featured Listings',
        value: formatNumber(props.stats.featured_cars),
        icon: Sparkles,
        accent: 'from-fuchsia-500/20 to-fuchsia-500/5',
    },
    {
        title: 'Total Views',
        value: formatNumber(props.stats.total_views),
        icon: Eye,
        accent: 'from-indigo-500/20 to-indigo-500/5',
    },
    {
        title: 'Average Mileage',
        value: `${formatNumber(props.stats.average_mileage)} km`,
        icon: Gauge,
        accent: 'from-cyan-500/20 to-cyan-500/5',
    },
];

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
        <section class="overflow-hidden rounded-2xl border border-sidebar-border bg-gradient-to-br from-[#0E1528] via-[#121C34] to-[#1D2232] p-6">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs tracking-[0.3em] text-[#94A3B8] uppercase">Inventory Command Center</p>
                    <h1 class="mt-2 text-3xl font-semibold text-white">AutoNest Admin Dashboard</h1>
                    <p class="mt-2 max-w-2xl text-sm text-[#CBD5E1]">
                        Monitor inventory performance, pricing health, and listing traction from one place.
                    </p>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-xl border border-white/15 bg-white/5 p-4">
                        <p class="text-xs text-[#94A3B8]">Inventory Value</p>
                        <p class="mt-1 text-xl font-semibold text-white">{{ formatPrice(stats.inventory_value) }}</p>
                    </div>
                    <div class="rounded-xl border border-white/15 bg-white/5 p-4">
                        <p class="text-xs text-[#94A3B8]">Average Price</p>
                        <p class="mt-1 text-xl font-semibold text-white">{{ formatPrice(stats.average_price) }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <article
                v-for="card in statCards"
                :key="card.title"
                class="rounded-2xl border border-sidebar-border bg-card p-5 shadow-sm"
            >
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-muted-foreground">{{ card.title }}</p>
                        <p class="mt-2 text-2xl font-semibold">{{ card.value }}</p>
                    </div>
                    <div :class="['rounded-xl bg-gradient-to-br p-2', card.accent]">
                        <component :is="card.icon" class="h-5 w-5 text-white" />
                    </div>
                </div>
            </article>
        </section>

        <section class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-2xl border border-sidebar-border bg-card p-5 lg:col-span-2">
                <h2 class="text-lg font-semibold">Performance Snapshot</h2>
                <div class="mt-5 space-y-5">
                    <div>
                        <div class="mb-2 flex items-center justify-between text-sm">
                            <span class="text-muted-foreground">Sell-through Rate</span>
                            <span class="font-semibold">{{ stats.sell_through_rate }}%</span>
                        </div>
                        <div class="h-2 rounded-full bg-muted">
                            <div class="h-2 rounded-full bg-emerald-500" :style="{ width: `${stats.sell_through_rate}%` }" />
                        </div>
                    </div>
                    <div>
                        <div class="mb-2 flex items-center justify-between text-sm">
                            <span class="text-muted-foreground">Featured Ratio</span>
                            <span class="font-semibold">{{ stats.featured_rate }}%</span>
                        </div>
                        <div class="h-2 rounded-full bg-muted">
                            <div class="h-2 rounded-full bg-fuchsia-500" :style="{ width: `${stats.featured_rate}%` }" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-sidebar-border bg-card p-5">
                <h2 class="text-lg font-semibold">Quick Actions</h2>
                <div class="mt-4 grid gap-3">
                    <Link :href="cars.create()" class="rounded-lg bg-primary px-4 py-2.5 text-center text-sm font-medium text-primary-foreground">
                        Add New Car
                    </Link>
                    <Link :href="cars.index()" class="rounded-lg border border-sidebar-border px-4 py-2.5 text-center text-sm font-medium">
                        Manage Listings
                    </Link>
                    <Link :href="settings.index()" class="rounded-lg border border-sidebar-border px-4 py-2.5 text-center text-sm font-medium">
                        Edit Site Settings
                    </Link>
                </div>
            </div>
        </section>

        <section class="grid gap-4 lg:grid-cols-2">
            <div class="rounded-2xl border border-sidebar-border bg-card p-5">
                <h2 class="text-lg font-semibold">Top Makes</h2>
                <div class="mt-4 space-y-3">
                    <div v-for="make in topMakes" :key="make.label" class="rounded-xl border border-sidebar-border p-3">
                        <div class="flex items-center justify-between text-sm">
                            <span>{{ make.label }}</span>
                            <span class="font-semibold">{{ make.count }} cars</span>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-muted">
                            <div class="h-2 rounded-full bg-sky-500" :style="{ width: `${make.percentage}%` }" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-sidebar-border bg-card p-5">
                <h2 class="text-lg font-semibold">Body Type Mix</h2>
                <div class="mt-4 space-y-3">
                    <div v-for="bodyType in bodyTypeMix" :key="bodyType.label" class="rounded-xl border border-sidebar-border p-3">
                        <div class="flex items-center justify-between text-sm">
                            <span>{{ bodyType.label }}</span>
                            <span class="font-semibold">{{ bodyType.count }} cars</span>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-muted">
                            <div class="h-2 rounded-full bg-indigo-500" :style="{ width: `${bodyType.percentage}%` }" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-4 xl:grid-cols-2">
            <div class="rounded-2xl border border-sidebar-border bg-card p-5">
                <h2 class="text-lg font-semibold">Top Viewed Listings</h2>
                <div class="mt-4 space-y-3">
                    <Link
                        v-for="car in topViewedCars"
                        :key="`viewed-${car.id}`"
                        :href="cars.show(car.slug)"
                        class="flex items-center justify-between rounded-xl border border-sidebar-border p-3 transition-colors hover:bg-muted/40"
                    >
                        <div>
                            <p class="font-medium">{{ car.title }}</p>
                            <p class="text-xs text-muted-foreground">{{ formatPrice(car.price) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold">{{ formatNumber(car.view_count) }} views</p>
                            <p class="text-xs" :class="car.is_sold ? 'text-rose-400' : 'text-emerald-400'">
                                {{ car.is_sold ? 'Sold' : 'Available' }}
                            </p>
                        </div>
                    </Link>
                </div>
            </div>

            <div class="rounded-2xl border border-sidebar-border bg-card p-5">
                <h2 class="text-lg font-semibold">Recently Added</h2>
                <div class="mt-4 space-y-3">
                    <Link
                        v-for="car in recentListings"
                        :key="`recent-${car.id}`"
                        :href="cars.edit(car.slug)"
                        class="flex items-center justify-between rounded-xl border border-sidebar-border p-3 transition-colors hover:bg-muted/40"
                    >
                        <div>
                            <p class="font-medium">{{ car.title }}</p>
                            <p class="text-xs text-muted-foreground">{{ new Date(car.created_at).toLocaleDateString() }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold">{{ formatPrice(car.price) }}</p>
                            <p class="text-xs" :class="car.is_featured ? 'text-fuchsia-400' : 'text-muted-foreground'">
                                {{ car.is_featured ? 'Featured' : 'Standard' }}
                            </p>
                        </div>
                    </Link>
                </div>
            </div>
        </section>
    </div>
</template>

