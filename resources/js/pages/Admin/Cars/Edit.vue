<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import adminCars from '@/routes/admin/cars';

const props = defineProps({
    car: { type: Object, required: true },
});

const form = useForm({
    title: props.car.title,
    make: props.car.make,
    model: props.car.model,
    year: props.car.year,
    price: props.car.price,
    mileage: props.car.mileage,
    fuel_type: props.car.fuel_type,
    transmission: props.car.transmission,
    body_type: props.car.body_type,
    color: props.car.color,
    condition: props.car.condition,
    description: props.car.description,
    features: props.car.features ?? [],
    existing_images: props.car.images ?? [],
    images: [],
    is_featured: props.car.is_featured,
    is_sold: props.car.is_sold,
});

function submit() {
    form.submit('put', adminCars.update(props.car.slug).url, {
        forceFormData: true,
    });
}
</script>

<template>
    <Head :title="`Edit ${car.title}`" />

    <div class="space-y-4 p-6">
        <h1 class="text-2xl font-semibold">Edit Car</h1>
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
            <div class="flex gap-3">
                <button class="rounded bg-primary px-4 py-2 text-primary-foreground" :disabled="form.processing">Update</button>
                <Link :href="adminCars.index()" class="rounded border border-sidebar-border px-4 py-2">Cancel</Link>
            </div>
        </form>
    </div>
</template>

