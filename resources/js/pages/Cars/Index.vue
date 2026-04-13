<script setup>
import { Head, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import CarFilters from '@/components/Cars/CarFilters.vue';
import CarGrid from '@/components/Cars/CarGrid.vue';
import Pagination from '@/components/UI/Pagination.vue';
import { index as carsIndex } from '@/routes/cars';

const props = defineProps({
    cars: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
    makes: { type: Array, default: () => [] },
});

const carsData = computed(() => props.cars.data ?? []);

function applyFilters(payload) {
    router.get(
        carsIndex(),
        {
            search: payload.search || undefined,
            make: payload.make || undefined,
            body_type: payload.body_type || undefined,
            fuel_type: payload.fuel_type || undefined,
            transmission: payload.transmission || undefined,
            condition: payload.condition || undefined,
            min_price: payload.min_price || undefined,
            max_price: payload.max_price || undefined,
            sort: payload.sort || 'newest',
        },
        { preserveState: true, replace: true },
    );
}

function resetFilters(payload) {
    applyFilters(payload);
}
</script>

<template>
    <Head title="Cars" />

    <section class="mx-auto max-w-7xl px-4 py-12">
        <div class="grid gap-6 lg:grid-cols-[320px_1fr]">
            <CarFilters :initial-filters="filters" :makes="makes" @apply="applyFilters" @reset="resetFilters" />

            <div>
                <div class="mb-5 flex flex-wrap items-center justify-between gap-2">
                    <h1 class="font-display text-4xl text-white">Cars for Sale</h1>
                    <p class="text-sm text-[#9CA3AF]">Showing {{ carsData.length }} of {{ cars.total ?? carsData.length }} cars</p>
                </div>

                <CarGrid :cars="carsData" />

                <Pagination :links="cars.links ?? []" />
            </div>
        </div>
    </section>
</template>

