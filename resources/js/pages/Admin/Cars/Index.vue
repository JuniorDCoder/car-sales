<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import adminCars from '@/routes/admin/cars';
import { useCurrency } from '@/composables/useCurrency';

defineProps({
    cars: { type: Object, required: true },
});

const { formatPrice } = useCurrency();

function removeCar(car) {
    if (!window.confirm(`Delete ${car.title}?`)) {
        return;
    }

    router.delete(adminCars.destroy(car.slug));
}
</script>

<template>
    <Head title="Manage Cars" />

    <div class="space-y-4 p-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Cars</h1>
            <Link :href="adminCars.create()" class="rounded bg-primary px-4 py-2 text-primary-foreground">Add New Car</Link>
        </div>

        <div class="overflow-x-auto rounded border border-sidebar-border">
            <table class="w-full text-left text-sm">
                <thead class="bg-muted">
                    <tr>
                        <th class="p-3">Title</th>
                        <th class="p-3">Make / Year</th>
                        <th class="p-3">Price</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Views</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="car in cars.data" :key="car.id" class="border-t border-sidebar-border">
                        <td class="p-3">{{ car.title }}</td>
                        <td class="p-3">{{ car.make }} / {{ car.year }}</td>
                        <td class="p-3">{{ formatPrice(car.price) }}</td>
                        <td class="p-3">{{ car.is_sold ? 'Sold' : 'Available' }}</td>
                        <td class="p-3">{{ car.view_count }}</td>
                        <td class="space-x-2 p-3">
                            <Link :href="adminCars.edit(car.slug)" class="text-primary underline">Edit</Link>
                            <button class="text-red-500" @click="removeCar(car)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

