<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { assetUrl } from '@/composables/useAssetUrl';
import adminCars from '@/routes/admin/cars';

const props = withDefaults(defineProps<{
    car: Record<string, any>;
    makeOptions?: string[];
    modelOptions?: string[];
    modelsByMake?: Record<string, string[]>;
}>(), {
    makeOptions: () => [],
    modelOptions: () => [],
    modelsByMake: () => ({}),
});

const customMake = ref(false);
const customModel = ref(false);
const imagePreviews = ref<File[]>([]);

const form = useForm<any>({
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
    features_text: (props.car.features ?? []).join(', '),
    existing_images: props.car.images ?? [],
    images: [],
    is_featured: props.car.is_featured,
    is_sold: props.car.is_sold,
});

const availableModels = computed(() => {
    const models = props.modelsByMake?.[form.make] ?? [];
    return Array.from(new Set(models));
});

watch(
    () => form.make,
    () => {
        if (!customModel.value && !availableModels.value.includes(form.model)) {
            form.model = availableModels.value[0] ?? form.model;
        }
    },
);

const previewUrls = computed(() => imagePreviews.value.map((file) => URL.createObjectURL(file)));

function onImagesSelected(event: Event) {
    const target = event.target as HTMLInputElement;
    const files = Array.from(target.files ?? []);
    imagePreviews.value = files.slice(0, Math.max(0, 10 - form.existing_images.length));
    form.images = imagePreviews.value;
}

function removeSelectedImage(index: number) {
    imagePreviews.value.splice(index, 1);
    form.images = [...imagePreviews.value];
}

function removeExistingImage(index: number) {
    form.existing_images.splice(index, 1);

    if (form.existing_images.length + imagePreviews.value.length > 10) {
        imagePreviews.value = imagePreviews.value.slice(0, Math.max(0, 10 - form.existing_images.length));
        form.images = [...imagePreviews.value];
    }
}

function submit() {
    form.transform((data: any) => ({
        ...data,
        _method: 'put',
        features: data.features_text
            .split(',')
            .map((feature: string) => feature.trim())
            .filter(Boolean),
    })).post(adminCars.update(props.car.slug).url, {
        forceFormData: true,
    });
}
</script>

<template>
    <Head :title="`Edit ${car.title}`" />

    <div class="space-y-6 p-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Edit Car</h1>
            <Link :href="adminCars.index()" class="rounded border border-sidebar-border px-4 py-2">Back to Cars</Link>
        </div>

        <form class="space-y-5 rounded-xl border border-sidebar-border p-5" @submit.prevent="submit">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm">Listing Title</label>
                    <input v-model="form.title" class="w-full rounded border border-sidebar-border px-3 py-2" placeholder="e.g. 2022 Mercedes-Benz C-Class" />
                    <p v-if="form.errors.title" class="text-sm text-red-500">{{ form.errors.title }}</p>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-sm">Make</label>
                        <button type="button" class="text-xs text-brand-400" @click="customMake = !customMake">
                            {{ customMake ? 'Select Existing' : 'Add New Make' }}
                        </button>
                    </div>
                    <input v-if="customMake" v-model="form.make" class="w-full rounded border border-sidebar-border px-3 py-2" placeholder="Type new make" />
                    <select v-else v-model="form.make" class="w-full rounded border border-sidebar-border bg-background px-3 py-2 text-foreground dark:bg-neutral-900 dark:text-neutral-100">
                        <option value="">Select make</option>
                        <option v-for="make in makeOptions" :key="make" :value="make">{{ make }}</option>
                    </select>
                    <p v-if="form.errors.make" class="text-sm text-red-500">{{ form.errors.make }}</p>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-sm">Model</label>
                        <button type="button" class="text-xs text-brand-400" @click="customModel = !customModel">
                            {{ customModel ? 'Select Existing' : 'Add New Model' }}
                        </button>
                    </div>
                    <input v-if="customModel" v-model="form.model" class="w-full rounded border border-sidebar-border px-3 py-2" placeholder="Type new model" />
                    <select v-else v-model="form.model" class="w-full rounded border border-sidebar-border bg-background px-3 py-2 text-foreground dark:bg-neutral-900 dark:text-neutral-100">
                        <option value="">Select model</option>
                        <option v-for="model in availableModels.length ? availableModels : modelOptions" :key="model" :value="model">{{ model }}</option>
                    </select>
                    <p v-if="form.errors.model" class="text-sm text-red-500">{{ form.errors.model }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm">Year</label>
                    <input v-model.number="form.year" type="number" class="w-full rounded border border-sidebar-border px-3 py-2" />
                    <p v-if="form.errors.year" class="text-sm text-red-500">{{ form.errors.year }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm">Price</label>
                    <input v-model.number="form.price" type="number" class="w-full rounded border border-sidebar-border px-3 py-2" />
                    <p v-if="form.errors.price" class="text-sm text-red-500">{{ form.errors.price }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm">Mileage</label>
                    <input v-model.number="form.mileage" type="number" class="w-full rounded border border-sidebar-border px-3 py-2" />
                    <p v-if="form.errors.mileage" class="text-sm text-red-500">{{ form.errors.mileage }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm">Color</label>
                    <input v-model="form.color" class="w-full rounded border border-sidebar-border px-3 py-2" />
                    <p v-if="form.errors.color" class="text-sm text-red-500">{{ form.errors.color }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm">Fuel Type</label>
                    <select v-model="form.fuel_type" class="w-full rounded border border-sidebar-border bg-background px-3 py-2 text-foreground dark:bg-neutral-900 dark:text-neutral-100">
                        <option>Petrol</option>
                        <option>Diesel</option>
                        <option>Electric</option>
                        <option>Hybrid</option>
                    </select>
                    <p v-if="form.errors.fuel_type" class="text-sm text-red-500">{{ form.errors.fuel_type }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm">Transmission</label>
                    <select v-model="form.transmission" class="w-full rounded border border-sidebar-border bg-background px-3 py-2 text-foreground dark:bg-neutral-900 dark:text-neutral-100">
                        <option>Automatic</option>
                        <option>Manual</option>
                    </select>
                    <p v-if="form.errors.transmission" class="text-sm text-red-500">{{ form.errors.transmission }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm">Body Type</label>
                    <select v-model="form.body_type" class="w-full rounded border border-sidebar-border bg-background px-3 py-2 text-foreground dark:bg-neutral-900 dark:text-neutral-100">
                        <option>Sedan</option>
                        <option>SUV</option>
                        <option>Pickup</option>
                        <option>Hatchback</option>
                        <option>Coupe</option>
                        <option>Van</option>
                        <option>Minivan</option>
                    </select>
                    <p v-if="form.errors.body_type" class="text-sm text-red-500">{{ form.errors.body_type }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm">Condition</label>
                    <select v-model="form.condition" class="w-full rounded border border-sidebar-border bg-background px-3 py-2 text-foreground dark:bg-neutral-900 dark:text-neutral-100">
                        <option>New</option>
                        <option>Used</option>
                        <option>Certified Pre-Owned</option>
                    </select>
                    <p v-if="form.errors.condition" class="text-sm text-red-500">{{ form.errors.condition }}</p>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm">Features (comma separated)</label>
                <input v-model="form.features_text" class="w-full rounded border border-sidebar-border px-3 py-2" placeholder="Leather Seats, Sunroof, Backup Camera" />
                <p v-if="form.errors.features" class="text-sm text-red-500">{{ form.errors.features }}</p>
                <p v-if="form.errors['features.0']" class="text-sm text-red-500">{{ form.errors['features.0'] }}</p>
            </div>

            <div class="space-y-2">
                <label class="text-sm">Description</label>
                <textarea v-model="form.description" rows="6" class="w-full rounded border border-sidebar-border px-3 py-2" />
                <p v-if="form.errors.description" class="text-sm text-red-500">{{ form.errors.description }}</p>
            </div>

            <div class="space-y-2">
                <label class="text-sm">Current Images ({{ form.existing_images.length }}/10)</label>
                <div v-if="form.existing_images.length" class="grid grid-cols-2 gap-3 md:grid-cols-5">
                    <div v-for="(image, index) in form.existing_images" :key="image" class="relative overflow-hidden rounded-lg border border-sidebar-border">
                        <img :src="assetUrl(image)" alt="Existing image" class="aspect-[4/3] w-full object-cover" />
                        <button type="button" class="absolute top-2 right-2 rounded bg-black/70 px-2 py-1 text-xs text-white" @click="removeExistingImage(index)">
                            Remove
                        </button>
                    </div>
                </div>
                <p v-else class="text-sm text-muted-foreground">No saved images. Upload new ones below.</p>
                <p v-if="form.errors.existing_images" class="text-sm text-red-500">{{ form.errors.existing_images }}</p>
            </div>

            <div class="space-y-2">
                <label class="text-sm">Upload More Images</label>
                <input type="file" accept="image/*" multiple class="w-full rounded border border-sidebar-border px-3 py-2" :disabled="form.existing_images.length >= 10" @change="onImagesSelected" />
                <p v-if="form.errors.images" class="text-sm text-red-500">{{ form.errors.images }}</p>
                <p v-if="form.errors['images.0']" class="text-sm text-red-500">{{ form.errors['images.0'] }}</p>
                <p v-if="form.existing_images.length >= 10" class="text-xs text-amber-500">Maximum images reached. Remove one to add another.</p>

                <div v-if="previewUrls.length" class="grid grid-cols-2 gap-3 md:grid-cols-5">
                    <div v-for="(url, index) in previewUrls" :key="url" class="relative overflow-hidden rounded-lg border border-sidebar-border">
                        <img :src="url" alt="New upload preview" class="aspect-[4/3] w-full object-cover" />
                        <button type="button" class="absolute top-2 right-2 rounded bg-black/70 px-2 py-1 text-xs text-white" @click="removeSelectedImage(index)">
                            Remove
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-6">
                <label class="inline-flex items-center gap-2 text-sm">
                    <input v-model="form.is_featured" type="checkbox" />
                    Featured listing
                </label>
                <label class="inline-flex items-center gap-2 text-sm">
                    <input v-model="form.is_sold" type="checkbox" />
                    Mark as sold
                </label>
            </div>

            <div class="flex gap-3">
                <button class="rounded bg-primary px-4 py-2 text-primary-foreground" :disabled="form.processing">Update Car</button>
                <Link :href="adminCars.index()" class="rounded border border-sidebar-border px-4 py-2">Cancel</Link>
            </div>
        </form>
    </div>
</template>

