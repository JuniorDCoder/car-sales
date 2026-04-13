<script setup>
import { reactive, watch } from 'vue';

const props = defineProps({
    initialFilters: {
        type: Object,
        default: () => ({}),
    },
    makes: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['apply', 'reset']);

const filters = reactive({
    search: '',
    make: '',
    body_type: '',
    fuel_type: '',
    transmission: '',
    condition: '',
    min_price: '',
    max_price: '',
    sort: 'newest',
});

watch(
    () => props.initialFilters,
    (value) => {
        Object.assign(filters, {
            search: value.search ?? '',
            make: value.make ?? '',
            body_type: value.body_type ?? '',
            fuel_type: value.fuel_type ?? '',
            transmission: value.transmission ?? '',
            condition: value.condition ?? '',
            min_price: value.min_price ?? '',
            max_price: value.max_price ?? '',
            sort: value.sort ?? 'newest',
        });
    },
    { immediate: true },
);

function applyFilters() {
    emit('apply', { ...filters });
}

function resetFilters() {
    Object.assign(filters, {
        search: '',
        make: '',
        body_type: '',
        fuel_type: '',
        transmission: '',
        condition: '',
        min_price: '',
        max_price: '',
        sort: 'newest',
    });
    emit('reset', { ...filters });
}
</script>

<template>
    <div class="space-y-4 rounded-2xl border border-white/10 bg-[#1A1A1A] p-5">
        <h3 class="font-display text-2xl text-white">Filters</h3>

        <input v-model="filters.search" placeholder="Search make or model" class="w-full rounded-lg border border-white/10 bg-[#111] px-3 py-2 text-sm" />

        <select v-model="filters.make" class="w-full rounded-lg border border-white/10 bg-[#111] px-3 py-2 text-sm">
            <option value="">All Makes</option>
            <option v-for="make in makes" :key="make" :value="make">{{ make }}</option>
        </select>

        <div class="grid grid-cols-2 gap-2">
            <input v-model="filters.min_price" type="number" placeholder="Min price" class="rounded-lg border border-white/10 bg-[#111] px-3 py-2 text-sm" />
            <input v-model="filters.max_price" type="number" placeholder="Max price" class="rounded-lg border border-white/10 bg-[#111] px-3 py-2 text-sm" />
        </div>

        <select v-model="filters.body_type" class="w-full rounded-lg border border-white/10 bg-[#111] px-3 py-2 text-sm">
            <option value="">Any Body Type</option>
            <option>Sedan</option>
            <option>SUV</option>
            <option>Pickup</option>
            <option>Hatchback</option>
            <option>Coupe</option>
            <option>Van</option>
            <option>Minivan</option>
        </select>

        <select v-model="filters.fuel_type" class="w-full rounded-lg border border-white/10 bg-[#111] px-3 py-2 text-sm">
            <option value="">Any Fuel Type</option>
            <option>Petrol</option>
            <option>Diesel</option>
            <option>Electric</option>
            <option>Hybrid</option>
        </select>

        <select v-model="filters.transmission" class="w-full rounded-lg border border-white/10 bg-[#111] px-3 py-2 text-sm">
            <option value="">Any Transmission</option>
            <option>Manual</option>
            <option>Automatic</option>
        </select>

        <select v-model="filters.condition" class="w-full rounded-lg border border-white/10 bg-[#111] px-3 py-2 text-sm">
            <option value="">Any Condition</option>
            <option>New</option>
            <option>Used</option>
            <option>Certified Pre-Owned</option>
        </select>

        <select v-model="filters.sort" class="w-full rounded-lg border border-white/10 bg-[#111] px-3 py-2 text-sm">
            <option value="newest">Newest</option>
            <option value="price_asc">Price ↑</option>
            <option value="price_desc">Price ↓</option>
            <option value="mileage_asc">Mileage ↑</option>
        </select>

        <div class="flex gap-2">
            <button type="button" class="flex-1 rounded-lg bg-brand-400 px-3 py-2 text-sm font-semibold text-black" @click="applyFilters">Apply</button>
            <button type="button" class="rounded-lg border border-white/10 px-3 py-2 text-sm text-[#9CA3AF]" @click="resetFilters">Reset</button>
        </div>
    </div>
</template>

