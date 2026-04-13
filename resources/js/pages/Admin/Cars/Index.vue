<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { assetUrl } from '@/composables/useAssetUrl';
import adminCars from '@/routes/admin/cars';
import { useCurrency } from '@/composables/useCurrency';

const props = withDefaults(defineProps<{
    cars: Record<string, any>;
    filters?: Record<string, any>;
    makeOptions?: string[];
    modelOptions?: string[];
}>(), {
    filters: () => ({}),
    makeOptions: () => [],
    modelOptions: () => [],
});

const { formatPrice } = useCurrency();

const filterForm = useForm<any>({
    search: props.filters.search ?? '',
    make: props.filters.make ?? '',
    model: props.filters.model ?? '',
    status: props.filters.status ?? '',
    featured_only: Boolean(props.filters.featured_only),
});

const deletingCar = ref<Record<string, any> | null>(null);

const activeFilterCount = computed(() => {
    return Object.values(filterForm.data()).filter((value) => {
        if (typeof value === 'boolean') {
            return value;
        }

        return value !== '' && value !== null && value !== undefined;
    }).length;
});

function applyFilters() {
    router.get(adminCars.index().url, filterForm.data(), {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

function resetFilters() {
    filterForm.reset();
    applyFilters();
}

function confirmDelete(car: Record<string, any>) {
    deletingCar.value = car;
}

function closeDeleteDialog() {
    deletingCar.value = null;
}

function deleteConfirmedCar() {
    if (!deletingCar.value) {
        return;
    }

    router.delete(adminCars.destroy(deletingCar.value.slug), {
        preserveScroll: true,
        onFinish: () => {
            closeDeleteDialog();
        },
    });
}

function normalizePageLabel(label: string) {
    return label.replace('&laquo;', '<<').replace('&raquo;', '>>').replace(/<[^>]+>/g, '').trim();
}
</script>

<template>
    <Head title="Manage Cars" />

    <div class="space-y-5 p-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold">Cars</h1>
                <p class="text-sm text-muted-foreground">Manage listings, track status, and quickly edit inventory details.</p>
            </div>
            <Link :href="adminCars.create()" class="rounded bg-primary px-4 py-2 text-primary-foreground">Add New Car</Link>
        </div>

        <form class="grid gap-3 rounded-xl border border-sidebar-border p-4 md:grid-cols-6" @submit.prevent="applyFilters">
            <input v-model="filterForm.search" class="rounded border border-sidebar-border px-3 py-2 md:col-span-2" placeholder="Search title, make, model" />

            <select v-model="filterForm.make" class="rounded border border-sidebar-border bg-background px-3 py-2 text-foreground dark:bg-neutral-900 dark:text-neutral-100">
                <option value="">All makes</option>
                <option v-for="make in makeOptions" :key="make" :value="make">{{ make }}</option>
            </select>

            <select v-model="filterForm.model" class="rounded border border-sidebar-border bg-background px-3 py-2 text-foreground dark:bg-neutral-900 dark:text-neutral-100">
                <option value="">All models</option>
                <option v-for="model in modelOptions" :key="model" :value="model">{{ model }}</option>
            </select>

            <select v-model="filterForm.status" class="rounded border border-sidebar-border bg-background px-3 py-2 text-foreground dark:bg-neutral-900 dark:text-neutral-100">
                <option value="">All status</option>
                <option value="available">Available</option>
                <option value="sold">Sold</option>
            </select>

            <label class="inline-flex items-center gap-2 rounded border border-sidebar-border px-3 py-2 text-sm">
                <input v-model="filterForm.featured_only" type="checkbox" />
                Featured only
            </label>

            <div class="flex gap-2 md:col-span-6">
                <button type="submit" class="rounded bg-primary px-4 py-2 text-primary-foreground">Apply Filters</button>
                <button type="button" class="rounded border border-sidebar-border px-4 py-2" @click="resetFilters">Reset</button>
                <span class="inline-flex items-center rounded border border-sidebar-border px-3 py-2 text-xs text-muted-foreground">
                    {{ activeFilterCount }} active filters
                </span>
            </div>
        </form>

        <div class="overflow-x-auto rounded border border-sidebar-border">
            <table class="w-full text-left text-sm">
                <thead class="bg-muted">
                    <tr>
                        <th class="p-3">Image</th>
                        <th class="p-3">Title</th>
                        <th class="p-3">Make / Model</th>
                        <th class="p-3">Price</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Views</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="car in cars.data" :key="car.id" class="border-t border-sidebar-border">
                        <td class="p-3">
                            <img :src="assetUrl(car.images?.[0])" :alt="car.title" class="h-14 w-24 rounded object-cover" />
                        </td>
                        <td class="p-3">
                            <p class="font-medium">{{ car.title }}</p>
                            <p class="text-xs text-muted-foreground">{{ car.year }}</p>
                        </td>
                        <td class="p-3">{{ car.make }} / {{ car.model }}</td>
                        <td class="p-3">{{ formatPrice(car.price) }}</td>
                        <td class="p-3">
                            <span class="rounded-full px-2 py-1 text-xs" :class="car.is_sold ? 'bg-rose-500/20 text-rose-400' : 'bg-emerald-500/20 text-emerald-400'">
                                {{ car.is_sold ? 'Sold' : 'Available' }}
                            </span>
                        </td>
                        <td class="p-3">{{ car.view_count }}</td>
                        <td class="space-x-2 p-3">
                            <Link :href="adminCars.edit(car.slug)" class="text-primary underline">Edit</Link>
                            <button class="text-rose-500" @click="confirmDelete(car)">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="cars.data.length === 0">
                        <td class="p-5 text-center text-muted-foreground" colspan="7">No cars match your filters.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="cars.links?.length" class="flex flex-wrap gap-2">
            <Link
                v-for="link in cars.links"
                :key="link.label"
                :href="link.url ?? ''"
                class="rounded border border-sidebar-border px-3 py-1 text-sm"
                :class="[{ 'bg-primary text-primary-foreground': link.active }, { 'pointer-events-none opacity-40': !link.url }]"
            >
                {{ normalizePageLabel(link.label) }}
            </Link>
        </div>
    </div>

    <Dialog :open="Boolean(deletingCar)" @update:open="(value) => { if (!value) closeDeleteDialog(); }">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Delete Car Listing?</DialogTitle>
                <DialogDescription>
                    This will permanently delete <span class="font-medium">{{ deletingCar?.title }}</span> and all its stored images.
                </DialogDescription>
            </DialogHeader>
            <DialogFooter>
                <button type="button" class="rounded border border-sidebar-border px-4 py-2" @click="closeDeleteDialog">Cancel</button>
                <button type="button" class="rounded bg-rose-600 px-4 py-2 text-white" @click="deleteConfirmedCar">Delete</button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

