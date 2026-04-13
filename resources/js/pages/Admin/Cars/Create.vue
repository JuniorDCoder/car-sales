<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import adminCars from '@/routes/admin/cars';

const form = useForm({
    title: '',
    make: '',
    model: '',
    year: new Date().getFullYear(),
    price: 0,
    mileage: null,
    fuel_type: 'Petrol',
    transmission: 'Automatic',
    body_type: 'Sedan',
    color: '',
    condition: 'Used',
    description: '',
    features: [],
    images: [],
    is_featured: false,
    is_sold: false,
});

function submit() {
    form.post(adminCars.store());
}
</script>

<template>
    <Head title="Create Car" />

    <div class="space-y-4 p-6">
        <h1 class="text-2xl font-semibold">Add New Car</h1>
        <form class="grid gap-3 rounded-xl border border-sidebar-border p-4" @submit.prevent="submit">
            <input v-model="form.title" class="rounded border border-sidebar-border px-3 py-2" placeholder="Title" />
            <p v-if="form.errors.title" class="text-sm text-red-500">{{ form.errors.title }}</p>
            <div class="grid gap-3 md:grid-cols-2">
                <input v-model="form.make" class="rounded border border-sidebar-border px-3 py-2" placeholder="Make" />
                <input v-model="form.model" class="rounded border border-sidebar-border px-3 py-2" placeholder="Model" />
                <input v-model.number="form.year" type="number" class="rounded border border-sidebar-border px-3 py-2" placeholder="Year" />
                <input v-model.number="form.price" type="number" class="rounded border border-sidebar-border px-3 py-2" placeholder="Price" />
            </div>
            <textarea v-model="form.description" rows="6" class="rounded border border-sidebar-border px-3 py-2" placeholder="Description" />
            <p v-if="form.errors.description" class="text-sm text-red-500">{{ form.errors.description }}</p>
            <div class="flex gap-3">
                <button class="rounded bg-primary px-4 py-2 text-primary-foreground" :disabled="form.processing">Save</button>
                <Link :href="adminCars.index()" class="rounded border border-sidebar-border px-4 py-2">Cancel</Link>
            </div>
        </form>
    </div>
</template>

