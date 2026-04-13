<script setup lang="ts">
import { computed, reactive, ref, watch } from 'vue';

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
    initialFilters?: Partial<CarFilterPayload>;
    makes?: string[];
    models?: string[];
    bodyTypeOptions?: string[];
    fuelTypeOptions?: string[];
    transmissionOptions?: string[];
    conditionOptions?: string[];
    priceRange?: { min: number; max: number };
    yearRange?: { min: number; max: number };
}>(), {
    makes: () => [],
    models: () => [],
    bodyTypeOptions: () => [],
    fuelTypeOptions: () => [],
    transmissionOptions: () => [],
    conditionOptions: () => [],
    priceRange: () => ({ min: 0, max: 0 }),
    yearRange: () => ({ min: new Date().getFullYear(), max: new Date().getFullYear() }),
});

const emit = defineEmits<{
    apply: [payload: CarFilterPayload];
    reset: [payload: CarFilterPayload];
}>();

const mobileOpen = ref(false);

const filters = reactive({
    search: '',
    make: '',
    model: '',
    body_type: [] as string[],
    fuel_type: [] as string[],
    transmission: [] as string[],
    condition: [] as string[],
    min_price: '',
    max_price: '',
    min_year: '',
    max_year: '',
    max_mileage: '',
    featured_only: false,
    sort: 'newest',
});

const activeFilterCount = computed(() => {
    let count = 0;

    if (filters.search) {
        count++;
    }

    if (filters.make) {
        count++;
    }

    if (filters.model) {
        count++;
    }

    count += filters.body_type.length;
    count += filters.fuel_type.length;
    count += filters.transmission.length;
    count += filters.condition.length;

    if (filters.min_price) {
        count++;
    }

    if (filters.max_price) {
        count++;
    }

    if (filters.min_year) {
        count++;
    }

    if (filters.max_year) {
        count++;
    }

    if (filters.max_mileage) {
        count++;
    }

    if (filters.featured_only) {
        count++;
    }

    if (filters.sort !== 'newest') {
        count++;
    }

    return count;
});

function normalizeMultiValue(value: unknown): string[] {
    if (Array.isArray(value)) {
        return value.map((item) => String(item)).filter(Boolean);
    }

    if (typeof value === 'string' && value.trim() !== '') {
        return [value];
    }

    return [];
}

function toggleMultiSelect(field: 'body_type' | 'fuel_type' | 'transmission' | 'condition', value: string) {
    const currentValues = filters[field];
    const exists = currentValues.includes(value);

    if (exists) {
        filters[field] = currentValues.filter((item) => item !== value);

        return;
    }

    filters[field] = [...currentValues, value];
}

function hasMultiValue(field: 'body_type' | 'fuel_type' | 'transmission' | 'condition', value: string): boolean {
    return filters[field].includes(value);
}

watch(
    () => props.initialFilters,
    (value) => {
        const current = value ?? {};

        Object.assign(filters, {
            search: current.search ?? '',
            make: current.make ?? '',
            model: current.model ?? '',
            body_type: normalizeMultiValue(current.body_type),
            fuel_type: normalizeMultiValue(current.fuel_type),
            transmission: normalizeMultiValue(current.transmission),
            condition: normalizeMultiValue(current.condition),
            min_price: current.min_price ?? '',
            max_price: current.max_price ?? '',
            min_year: current.min_year ?? '',
            max_year: current.max_year ?? '',
            max_mileage: current.max_mileage ?? '',
            featured_only: Boolean(current.featured_only),
            sort: current.sort ?? 'newest',
        });
    },
    { immediate: true },
);

function applyFilters() {
    emit('apply', { ...filters });
    mobileOpen.value = false;
}

function resetFilters() {
    Object.assign(filters, {
        search: '',
        make: '',
        model: '',
        body_type: [],
        fuel_type: [],
        transmission: [],
        condition: [],
        min_price: '',
        max_price: '',
        min_year: '',
        max_year: '',
        max_mileage: '',
        featured_only: false,
        sort: 'newest',
    });
    emit('reset', { ...filters });
    mobileOpen.value = false;
}
</script>

<template>
    <div class="space-y-3">
        <button
            type="button"
            class="flex w-full items-center justify-between rounded-xl border border-white/15 bg-[#181818] px-4 py-3 text-sm text-white lg:hidden"
            @click="mobileOpen = !mobileOpen"
        >
            <span class="font-semibold">Filters</span>
            <span class="rounded-full bg-white/10 px-2 py-0.5 text-xs">{{ activeFilterCount }} active</span>
        </button>

        <div
            class="space-y-4 rounded-2xl border border-white/10 bg-[#1A1A1A] p-5"
            :class="mobileOpen ? 'block' : 'hidden lg:block'"
        >
            <div class="flex items-center justify-between">
                <h3 class="font-display text-2xl text-white">Filters</h3>
                <span class="rounded-full bg-white/10 px-2 py-1 text-xs text-[#D1D5DB]">{{ activeFilterCount }} active</span>
            </div>

            <input v-model="filters.search" placeholder="Search title, make or model" class="w-full rounded-lg border border-white/10 bg-[#111] px-3 py-2 text-sm" />

            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                <select v-model="filters.make" class="w-full rounded-lg border border-white/10 bg-[#111] px-3 py-2 text-sm">
                    <option value="">All Makes</option>
                    <option v-for="make in props.makes" :key="make" :value="make">{{ make }}</option>
                </select>

                <select v-model="filters.model" class="w-full rounded-lg border border-white/10 bg-[#111] px-3 py-2 text-sm">
                    <option value="">All Models</option>
                    <option v-for="model in props.models" :key="model" :value="model">{{ model }}</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <input v-model="filters.min_price" type="number" :placeholder="`Min ${props.priceRange.min}`" class="rounded-lg border border-white/10 bg-[#111] px-3 py-2 text-sm" />
                <input v-model="filters.max_price" type="number" :placeholder="`Max ${props.priceRange.max}`" class="rounded-lg border border-white/10 bg-[#111] px-3 py-2 text-sm" />
            </div>

            <div class="grid grid-cols-2 gap-2">
                <input v-model="filters.min_year" type="number" :placeholder="`From ${props.yearRange.min}`" class="rounded-lg border border-white/10 bg-[#111] px-3 py-2 text-sm" />
                <input v-model="filters.max_year" type="number" :placeholder="`To ${props.yearRange.max}`" class="rounded-lg border border-white/10 bg-[#111] px-3 py-2 text-sm" />
            </div>

            <input v-model="filters.max_mileage" type="number" placeholder="Maximum mileage" class="w-full rounded-lg border border-white/10 bg-[#111] px-3 py-2 text-sm" />

            <div class="space-y-2">
                <p class="text-xs font-semibold tracking-[0.18em] text-[#9CA3AF] uppercase">Body Type</p>
                <div class="grid grid-cols-2 gap-2">
                    <button
                        v-for="option in props.bodyTypeOptions"
                        :key="`body-${option}`"
                        type="button"
                        class="rounded-lg border px-2 py-1.5 text-xs"
                        :class="hasMultiValue('body_type', option) ? 'border-brand-400 bg-brand-400/10 text-brand-300' : 'border-white/10 text-[#D1D5DB]'"
                        @click="toggleMultiSelect('body_type', option)"
                    >
                        {{ option }}
                    </button>
                </div>
            </div>

            <div class="space-y-2">
                <p class="text-xs font-semibold tracking-[0.18em] text-[#9CA3AF] uppercase">Fuel Type</p>
                <div class="grid grid-cols-2 gap-2">
                    <button
                        v-for="option in props.fuelTypeOptions"
                        :key="`fuel-${option}`"
                        type="button"
                        class="rounded-lg border px-2 py-1.5 text-xs"
                        :class="hasMultiValue('fuel_type', option) ? 'border-brand-400 bg-brand-400/10 text-brand-300' : 'border-white/10 text-[#D1D5DB]'"
                        @click="toggleMultiSelect('fuel_type', option)"
                    >
                        {{ option }}
                    </button>
                </div>
            </div>

            <div class="space-y-2">
                <p class="text-xs font-semibold tracking-[0.18em] text-[#9CA3AF] uppercase">Transmission</p>
                <div class="grid grid-cols-2 gap-2">
                    <button
                        v-for="option in props.transmissionOptions"
                        :key="`transmission-${option}`"
                        type="button"
                        class="rounded-lg border px-2 py-1.5 text-xs"
                        :class="hasMultiValue('transmission', option) ? 'border-brand-400 bg-brand-400/10 text-brand-300' : 'border-white/10 text-[#D1D5DB]'"
                        @click="toggleMultiSelect('transmission', option)"
                    >
                        {{ option }}
                    </button>
                </div>
            </div>

            <div class="space-y-2">
                <p class="text-xs font-semibold tracking-[0.18em] text-[#9CA3AF] uppercase">Condition</p>
                <div class="grid grid-cols-2 gap-2">
                    <button
                        v-for="option in props.conditionOptions"
                        :key="`condition-${option}`"
                        type="button"
                        class="rounded-lg border px-2 py-1.5 text-xs"
                        :class="hasMultiValue('condition', option) ? 'border-brand-400 bg-brand-400/10 text-brand-300' : 'border-white/10 text-[#D1D5DB]'"
                        @click="toggleMultiSelect('condition', option)"
                    >
                        {{ option }}
                    </button>
                </div>
            </div>

            <label class="inline-flex items-center gap-2 text-sm text-[#D1D5DB]">
                <input v-model="filters.featured_only" type="checkbox" class="rounded border-white/10 bg-[#111]" />
                Featured listings only
            </label>

            <select v-model="filters.sort" class="w-full rounded-lg border border-white/10 bg-[#111] px-3 py-2 text-sm">
                <option value="newest">Newest</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
                <option value="mileage_asc">Mileage: Low to High</option>
                <option value="year_desc">Year: Newest First</option>
            </select>

            <div class="flex gap-2">
                <button type="button" class="flex-1 rounded-lg bg-brand-400 px-3 py-2 text-sm font-semibold text-black" @click="applyFilters">Apply</button>
                <button type="button" class="rounded-lg border border-white/10 px-3 py-2 text-sm text-[#9CA3AF]" @click="resetFilters">Reset</button>
            </div>
        </div>
    </div>
</template>

