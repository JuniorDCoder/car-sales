<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { store as contactStore } from '@/routes/contact';
import { index as carsIndex } from '@/routes/cars';
import CarGrid from '@/components/Cars/CarGrid.vue';
import CarImageGallery from '@/components/Cars/CarImageGallery.vue';
import { useCurrency } from '@/composables/useCurrency';

const props = withDefaults(defineProps<{
    car: Record<string, any>;
    similarCars?: Array<Record<string, any>>;
    whatsappNumber?: string;
    whatsappMessage?: string;
    contactEmail?: string;
    whatsappEnabled?: boolean;
}>(), {
    similarCars: () => [],
    whatsappNumber: '',
    whatsappMessage: '',
    contactEmail: '',
    whatsappEnabled: true,
});

const page = usePage();
const { formatPrice } = useCurrency();

const resolvedContactEmail = computed(() => {
    const fromSettings = (page.props.settings?.contact_email ?? '').toString().trim();
    if (fromSettings) {
        return fromSettings;
    }

    return (props.contactEmail ?? '').trim();
});

const canShowWhatsApp = computed(() => {
    const fromSettings = page.props.settings?.whatsapp_enabled;
    if (typeof fromSettings === 'boolean') {
        return fromSettings;
    }

    return Boolean(props.whatsappEnabled);
});

const whatsappLink = computed(() => {
    if (!canShowWhatsApp.value) {
        return null;
    }

    const phone = (props.whatsappNumber ?? '').replace(/[^\d]/g, '');

    if (!phone) {
        return null;
    }

    const text = encodeURIComponent(`Hi, I'm interested in the ${props.car.title} listed for ${formatPrice(props.car.price)}.`);

    return `https://wa.me/${phone}?text=${text}`;
});

const form = useForm({
    name: '',
    email: '',
    phone: '',
    message: `Hi, I'm interested in the ${props.car.title}.`,
});

function submit() {
    form.post(contactStore().url, {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head :title="car.title" />

    <section class="mx-auto grid max-w-7xl gap-8 px-4 py-12 lg:grid-cols-2">
        <div class="space-y-4">
            <CarImageGallery :images="car.images ?? []" />
            <p class="text-xs text-[#9CA3AF]">Tip: Use thumbnails and next/prev controls to preview all available photos.</p>
        </div>
        <div>
            <h1 class="font-display text-4xl">{{ car.title }}</h1>
            <p class="mt-2 text-4xl font-bold text-brand-400">{{ formatPrice(car.price) }}</p>
            <p class="mt-2 inline-flex rounded-full px-3 py-1 text-xs" :class="car.is_sold ? 'bg-red-500/20 text-red-300' : 'bg-green-500/20 text-green-300'">
                {{ car.is_sold ? 'Sold' : 'Available' }}
            </p>

            <div class="mt-5 grid grid-cols-2 gap-3 rounded-xl border border-white/10 bg-[#171717] p-4 text-sm text-[#D1D5DB]">
                <p><span class="text-[#9CA3AF]">Year:</span> {{ car.year }}</p>
                <p><span class="text-[#9CA3AF]">Make:</span> {{ car.make }}</p>
                <p><span class="text-[#9CA3AF]">Model:</span> {{ car.model }}</p>
                <p><span class="text-[#9CA3AF]">Mileage:</span> {{ car.mileage ?? 0 }} mi</p>
                <p><span class="text-[#9CA3AF]">Fuel:</span> {{ car.fuel_type }}</p>
                <p><span class="text-[#9CA3AF]">Transmission:</span> {{ car.transmission }}</p>
                <p><span class="text-[#9CA3AF]">Body:</span> {{ car.body_type }}</p>
                <p><span class="text-[#9CA3AF]">Condition:</span> {{ car.condition }}</p>
            </div>

            <p class="mt-6 whitespace-pre-line rounded-xl border border-white/10 bg-[#141414] p-4 text-[#D1D5DB]">{{ car.description }}</p>

            <div v-if="(car.features ?? []).length" class="mt-6 flex flex-wrap gap-2">
                <span v-for="feature in car.features" :key="feature" class="rounded-full border border-brand-400/40 px-3 py-1 text-xs text-brand-400">
                    {{ feature }}
                </span>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a v-if="whatsappLink" :href="whatsappLink" target="_blank" rel="noopener noreferrer" class="rounded bg-green-500 px-4 py-2 font-semibold text-black">WhatsApp Us About This Car</a>
                <a :href="`mailto:${resolvedContactEmail}`" class="rounded border border-brand-400 px-4 py-2 text-brand-400">Send Email Enquiry</a>
            </div>
        </div>
    </section>

    <section v-if="similarCars.length" class="mx-auto max-w-7xl px-4 pb-16">
        <div class="mb-6 flex items-end justify-between">
            <h2 class="font-display text-3xl">Similar Cars</h2>
            <Link :href="carsIndex({ query: { make: car.make } })" class="text-sm text-brand-400">View more {{ car.make }}</Link>
        </div>
        <CarGrid :cars="similarCars" :show-whatsapp="false" />
    </section>

    <section class="mx-auto max-w-7xl px-4 pb-16">
        <h2 class="font-display text-2xl">Quick Enquiry</h2>
        <form class="mt-4 grid gap-3 rounded border border-white/10 bg-[#1A1A1A] p-4" @submit.prevent="submit">
            <input v-model="form.name" class="rounded border border-white/10 bg-[#111] px-3 py-2" placeholder="Name" />
            <p v-if="form.errors.name" class="text-sm text-red-400">{{ form.errors.name }}</p>
            <input v-model="form.email" class="rounded border border-white/10 bg-[#111] px-3 py-2" placeholder="Email" />
            <p v-if="form.errors.email" class="text-sm text-red-400">{{ form.errors.email }}</p>
            <input v-model="form.phone" class="rounded border border-white/10 bg-[#111] px-3 py-2" placeholder="Phone" />
            <textarea v-model="form.message" rows="4" class="rounded border border-white/10 bg-[#111] px-3 py-2" />
            <p v-if="form.errors.message" class="text-sm text-red-400">{{ form.errors.message }}</p>
            <button class="rounded bg-brand-400 px-4 py-2 font-semibold text-black" :disabled="form.processing">Send Message</button>
        </form>
    </section>
</template>

