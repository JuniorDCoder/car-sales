<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { BadgeCheck, CircleDollarSign, Handshake, MessageCircle } from 'lucide-vue-next';
import { index as carsIndex, show as carsShow } from '@/routes/cars';
import { index as contactIndex } from '@/routes/contact';
import CarGrid from '@/components/Cars/CarGrid.vue';

const props = defineProps({
    featuredCars: { type: Array, default: () => [] },
    latestCars: { type: Array, default: () => [] },
    heroHeadline: { type: String, default: 'Find Your Perfect Car Today' },
    heroSubheadline: { type: String, default: 'Premium inventory with trusted support.' },
    whatsappNumber: { type: String, default: '' },
    whatsappMessage: { type: String, default: '' },
});

function whatsappUrl() {
    const phone = (props.whatsappNumber ?? '').replace(/[^\d]/g, '');
    return `https://wa.me/${phone}?text=${encodeURIComponent(props.whatsappMessage || 'Hello')}`;
}

const bodyTypes = ['Sedan', 'SUV', 'Pickup', 'Hatchback', 'Coupe', 'Van', 'Minivan'];
</script>

<template>
    <Head title="Home" />

    <section class="relative flex min-h-screen items-center overflow-hidden px-4 py-20">
        <img src="/images/car-placeholder.svg" alt="Hero vehicle" class="absolute inset-0 h-full w-full object-cover" />
        <div class="absolute inset-0 bg-black/70" />
        <div class="relative mx-auto max-w-4xl text-center">
            <h1 class="animate-fade-in font-display text-5xl font-semibold text-white md:text-6xl">{{ heroHeadline }}</h1>
            <p class="mx-auto mt-5 max-w-2xl text-[#9CA3AF]">{{ heroSubheadline }}</p>
            <div class="mt-8 flex justify-center gap-3">
                <Link :href="carsIndex()" class="rounded bg-brand-400 px-5 py-3 font-semibold text-black transition-all duration-300 hover:bg-brand-500">Browse Cars</Link>
                <Link :href="contactIndex()" class="rounded border border-brand-400 px-5 py-3 text-brand-400 transition-all duration-300 hover:bg-brand-400 hover:text-black">Contact Us</Link>
            </div>
        </div>
    </section>

    <section class="border-y border-white/5 bg-[#121212]">
        <div class="mx-auto grid max-w-7xl grid-cols-2 gap-6 px-4 py-8 text-center sm:grid-cols-4">
            <div><p class="text-3xl font-bold text-brand-400">120+</p><p class="text-sm text-[#9CA3AF]">Cars Available</p></div>
            <div><p class="text-3xl font-bold text-brand-400">2012</p><p class="text-sm text-[#9CA3AF]">Trusted Since</p></div>
            <div><p class="text-3xl font-bold text-brand-400">3k+</p><p class="text-sm text-[#9CA3AF]">Happy Customers</p></div>
            <div><p class="text-3xl font-bold text-brand-400">45+</p><p class="text-sm text-[#9CA3AF]">Models & Makes</p></div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-20">
        <h2 class="font-display text-3xl">Featured Cars</h2>
        <p class="mt-2 text-[#9CA3AF]">Explore hand-picked premium inventory.</p>
        <div class="mt-8">
            <CarGrid :cars="featuredCars" />
        </div>

        <div class="mt-10 text-center">
            <Link :href="carsIndex()" class="rounded border border-brand-400 px-4 py-2 text-brand-400 transition-all duration-300 hover:bg-brand-400 hover:text-black">View All Cars</Link>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12">
        <h2 class="font-display text-3xl">Why Choose Us</h2>
        <div class="mt-8 grid gap-4 md:grid-cols-4">
            <div class="rounded-2xl border border-white/10 bg-[#1A1A1A] p-5"><CircleDollarSign class="h-6 w-6 text-brand-400" /><p class="mt-3 font-semibold">Easy Financing</p><p class="mt-1 text-sm text-[#9CA3AF]">Flexible plans for every buyer profile.</p></div>
            <div class="rounded-2xl border border-white/10 bg-[#1A1A1A] p-5"><BadgeCheck class="h-6 w-6 text-brand-400" /><p class="mt-3 font-semibold">Verified Cars</p><p class="mt-1 text-sm text-[#9CA3AF]">Thoroughly checked and documented inventory.</p></div>
            <div class="rounded-2xl border border-white/10 bg-[#1A1A1A] p-5"><MessageCircle class="h-6 w-6 text-brand-400" /><p class="mt-3 font-semibold">WhatsApp Support</p><p class="mt-1 text-sm text-[#9CA3AF]">Fast responses from our sales specialists.</p></div>
            <div class="rounded-2xl border border-white/10 bg-[#1A1A1A] p-5"><Handshake class="h-6 w-6 text-brand-400" /><p class="mt-3 font-semibold">Best Prices</p><p class="mt-1 text-sm text-[#9CA3AF]">Competitive pricing with premium value.</p></div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12">
        <h2 class="font-display text-3xl">Browse by Body Type</h2>
        <div class="mt-6 flex gap-3 overflow-x-auto pb-2">
            <Link
                v-for="bodyType in bodyTypes"
                :key="bodyType"
                :href="carsIndex({ query: { body_type: bodyType } })"
                class="rounded-full border border-white/15 px-4 py-2 text-sm text-[#D1D5DB] transition-all duration-300 hover:border-brand-400 hover:text-brand-400"
            >
                {{ bodyType }}
            </Link>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12">
        <div class="rounded-2xl border border-brand-400/30 bg-[#1A1A1A] p-8 text-center">
            <h3 class="font-display text-3xl">Find Your Perfect Car Today</h3>
            <p class="mt-2 text-[#9CA3AF]">Browse premium vehicles or speak to our team instantly.</p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <Link :href="carsIndex()" class="rounded bg-brand-400 px-5 py-3 font-semibold text-black transition-all duration-300 hover:bg-brand-500">Browse All Cars</Link>
                <a :href="whatsappUrl()" target="_blank" rel="noopener noreferrer" class="rounded border border-green-500 px-5 py-3 font-semibold text-green-500 transition-all duration-300 hover:bg-green-500 hover:text-black">
                    Contact on WhatsApp
                </a>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12">
        <div class="mb-8 flex items-end justify-between">
            <h2 class="font-display text-3xl">Latest Arrivals</h2>
            <Link :href="carsIndex()" class="text-sm text-brand-400">See all</Link>
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <Link v-for="car in latestCars" :key="`latest-${car.id}`" :href="carsShow(car.slug)" class="rounded-xl border border-white/10 bg-[#1A1A1A] p-4 transition-all duration-300 hover:border-brand-400/30">
                <p class="truncate font-semibold">{{ car.title }}</p>
                <p class="text-sm text-[#9CA3AF]">{{ car.year }} · {{ car.make }}</p>
            </Link>
        </div>
    </section>
</template>

