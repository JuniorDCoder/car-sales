<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Fuel, Gauge, Mail, MessageCircle, Settings2 } from 'lucide-vue-next';
import { show as carsShow } from '@/routes/cars';
import { assetUrl } from '@/composables/useAssetUrl';
import { useCurrency } from '@/composables/useCurrency';

const props = defineProps({
    car: {
        type: Object,
        required: true,
    },
    showWhatsapp: {
        type: Boolean,
        default: true,
    },
});

const page = usePage();
const { formatPrice } = useCurrency();

const isWhatsappEnabled = computed(() => Boolean(page.props.settings?.whatsapp_enabled));
const contactEmail = computed(() => (page.props.settings?.contact_email ?? '').toString().trim());
const mailtoLink = computed(() => {
    if (!contactEmail.value) {
        return null;
    }

    return `mailto:${contactEmail.value}`;
});

const whatsappLink = computed(() => {
    if (!isWhatsappEnabled.value) {
        return null;
    }

    const phone = (page.props.settings?.whatsapp_number ?? '').toString().replace(/[^\d]/g, '');

    if (!phone) {
        return null;
    }

    const message = encodeURIComponent(
        `Hi, I am interested in the ${props.car.title} listed for ${formatPrice(props.car.price)}.`,
    );

    return `https://wa.me/${phone}?text=${message}`;
});
</script>

<template>
    <article class="overflow-hidden rounded-2xl border border-white/5 bg-[#1A1A1A] shadow-lg shadow-black/40 transition-all duration-300 hover:border-brand-400/30">
        <div class="relative">
            <img :src="assetUrl(car.primary_image ?? car.images?.[0])" :alt="car.title" class="aspect-[4/3] w-full object-cover" />
            <div class="absolute top-3 left-3 flex gap-2 text-xs">
                <span v-if="car.is_featured" class="rounded-full bg-brand-400 px-2 py-1 font-semibold text-black">Featured</span>
                <span v-if="car.condition === 'New'" class="rounded-full bg-green-500 px-2 py-1 font-semibold text-black">New</span>
                <span v-if="car.is_sold" class="rounded-full bg-red-500 px-2 py-1 font-semibold text-white">Sold</span>
            </div>
        </div>

        <div class="space-y-4 p-5">
            <div>
                <h3 class="truncate text-lg font-semibold text-[#F5F5F5]">{{ car.title }}</h3>
                <p class="mt-1 text-2xl font-bold text-brand-400">{{ formatPrice(car.price) }}</p>
            </div>

            <div class="grid grid-cols-2 gap-y-2 text-xs text-[#9CA3AF]">
                <span>{{ car.year }}</span>
                <span class="text-right">{{ car.mileage ?? 0 }} mi</span>
                <span class="inline-flex items-center gap-1"><Fuel class="h-3.5 w-3.5" />{{ car.fuel_type }}</span>
                <span class="inline-flex items-center justify-end gap-1"><Settings2 class="h-3.5 w-3.5" />{{ car.transmission }}</span>
            </div>

            <div class="flex items-center gap-2">
                <Link :href="carsShow(car.slug)" class="flex-1 rounded-lg bg-brand-400 px-3 py-2 text-center text-sm font-semibold text-black transition-all duration-300 hover:bg-brand-500">
                    View Details
                </Link>
                <a
                    v-if="showWhatsapp && whatsappLink"
                    :href="whatsappLink"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="rounded-lg border border-green-500 px-3 py-2 text-green-500 transition-all duration-300 hover:bg-green-500 hover:text-black"
                    aria-label="Contact on WhatsApp"
                >
                    <MessageCircle class="h-4 w-4" />
                </a>
                <a
                    v-else-if="showWhatsapp && mailtoLink"
                    :href="mailtoLink"
                    class="rounded-lg border border-brand-400 px-3 py-2 text-brand-400 transition-all duration-300 hover:bg-brand-400 hover:text-black"
                    aria-label="Send email enquiry"
                >
                    <Mail class="h-4 w-4" />
                </a>
                <span v-else class="rounded-lg border border-white/10 px-3 py-2 text-[#6B7280]"><Gauge class="h-4 w-4" /></span>
            </div>
        </div>
    </article>
</template>

