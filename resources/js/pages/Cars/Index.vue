<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import CarFilters from '@/components/Cars/CarFilters.vue';
import CarGrid from '@/components/Cars/CarGrid.vue';
import Pagination from '@/components/UI/Pagination.vue';
import { index as carsIndex } from '@/routes/cars';
import { useCurrency } from '@/composables/useCurrency';

type CarsPagination = {
    data?: Array<Record<string, any>>;
    total?: number;
    links?: Array<{ url: string | null; label: string; active: boolean }>;
};

type CarFilterPayload = {
    search: string;
    make: string;
    model: string;
    body_type: string[];
    fuel_type: string[];
    transmission: string[];
    condition: string[];
    min_price: string;
    max_price: string;
    min_year: string;
    max_year: string;
    max_mileage: string;
    featured_only: boolean;
    sort: string;
};

const props = withDefaults(defineProps<{
    cars: CarsPagination;
    filters?: Record<string, any>;
    makes?: string[];
    models?: string[];
    bodyTypeOptions?: string[];
    fuelTypeOptions?: string[];
    transmissionOptions?: string[];
    conditionOptions?: string[];
    priceRange?: { min: number; max: number };
    yearRange?: { min: number; max: number };
}>(), {
    filters: () => ({}),
    makes: () => [],
    models: () => [],
    bodyTypeOptions: () => [],
    fuelTypeOptions: () => [],
    transmissionOptions: () => [],
    conditionOptions: () => [],
    priceRange: () => ({ min: 0, max: 0 }),
    yearRange: () => ({ min: new Date().getFullYear(), max: new Date().getFullYear() }),
});

const { formatPrice } = useCurrency();
const carsData = computed(() => props.cars.data ?? []);
const activeFilterLabels = computed(() => {
    const labels: string[] = [];
    const current = props.filters ?? {};

    if (current.search) {
        labels.push(`Search: ${current.search}`);
    }

    if (current.make) {
        labels.push(`Make: ${current.make}`);
    }

    if (current.model) {
        labels.push(`Model: ${current.model}`);
    }

    if (Array.isArray(current.body_type)) {
        labels.push(...current.body_type.map((value: string) => `Body: ${value}`));
    }

    if (Array.isArray(current.fuel_type)) {
        labels.push(...current.fuel_type.map((value: string) => `Fuel: ${value}`));
    }

    if (current.min_price) {
        labels.push(`Min Price: ${current.min_price}`);
    }

    if (current.max_price) {
        labels.push(`Max Price: ${current.max_price}`);
    }

    if (current.featured_only) {
        labels.push('Featured only');
    }

    return labels;
});

function applyFilters(payload: CarFilterPayload) {
    router.get(
        carsIndex(),
        {
            search: payload.search || undefined,
            make: payload.make || undefined,
            model: payload.model || undefined,
            body_type: payload.body_type.length ? payload.body_type : undefined,
            fuel_type: payload.fuel_type.length ? payload.fuel_type : undefined,
            transmission: payload.transmission.length ? payload.transmission : undefined,
            condition: payload.condition.length ? payload.condition : undefined,
            min_price: payload.min_price || undefined,
            max_price: payload.max_price || undefined,
            min_year: payload.min_year || undefined,
            max_year: payload.max_year || undefined,
            max_mileage: payload.max_mileage || undefined,
            featured_only: payload.featured_only ? 1 : undefined,
            sort: payload.sort || 'newest',
        },
        { preserveState: true, replace: true },
    );
}

function resetFilters(payload: CarFilterPayload) {
    applyFilters(payload);
}
</script>

<template>
    <Head title="Cars" />

    <section class="mx-auto max-w-7xl px-4 py-10 sm:py-12">
        <div class="mb-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-white/10 bg-[#181818] p-4">
                <p class="text-xs text-[#9CA3AF]">Available Listings</p>
                <p class="mt-2 text-2xl font-semibold text-white">{{ cars.total ?? carsData.length }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-[#181818] p-4">
                <p class="text-xs text-[#9CA3AF]">Price Window</p>
                <p class="mt-2 text-sm text-white">{{ formatPrice(priceRange.min) }} - {{ formatPrice(priceRange.max) }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-[#181818] p-4">
                <p class="text-xs text-[#9CA3AF]">Model Years</p>
                <p class="mt-2 text-sm text-white">{{ yearRange.min }} - {{ yearRange.max }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-[#181818] p-4">
                <p class="text-xs text-[#9CA3AF]">Popular Makes</p>
                <p class="mt-2 text-sm text-white">{{ makes.slice(0, 3).join(' • ') || 'Coming soon' }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[320px_1fr]">
            <CarFilters
                :initial-filters="filters"
                :makes="makes"
                :models="models"
                :body-type-options="bodyTypeOptions"
                :fuel-type-options="fuelTypeOptions"
                :transmission-options="transmissionOptions"
                :condition-options="conditionOptions"
                :price-range="priceRange"
                :year-range="yearRange"
                @apply="applyFilters"
                @reset="resetFilters"
            />

            <div>
                <div class="mb-5 flex flex-wrap items-center justify-between gap-2">
                    <h1 class="font-display text-4xl text-white">Cars for Sale</h1>
                    <p class="text-sm text-[#9CA3AF]">Showing {{ carsData.length }} of {{ cars.total ?? carsData.length }} cars</p>
                </div>

                <div v-if="activeFilterLabels.length" class="mb-5 flex flex-wrap gap-2">
                    <span v-for="label in activeFilterLabels" :key="label" class="rounded-full border border-brand-400/40 bg-brand-400/10 px-3 py-1 text-xs text-brand-300">
                        {{ label }}
                    </span>
                </div>

                <CarGrid :cars="carsData" />

                <Pagination :links="cars.links ?? []" />
            </div>
        </div>
    </section>
</template>

