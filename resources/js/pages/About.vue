<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { BadgeCheck, Gem, Shield, Sparkles, Users, Wrench } from 'lucide-vue-next';
import { index as carsIndex } from '@/routes/cars';
import { index as contactIndex } from '@/routes/contact';
import { assetUrl } from '@/composables/useAssetUrl';

const page = usePage();

const props = withDefaults(defineProps<{
    aboutCars?: Array<Record<string, any>>;
    teamStats?: {
        happy_buyers?: string;
        cars_delivered?: string;
        dealer_rating?: string;
    };
}>(), {
    aboutCars: () => [],
    teamStats: () => ({
        happy_buyers: '4,800+',
        cars_delivered: '9,200+',
        dealer_rating: '4.9/5',
    }),
});

const highlights = [
    {
        title: 'Trusted Vehicles',
        text: 'Every listing goes through quality and documentation checks before publishing.',
        icon: Shield,
    },
    {
        title: 'Premium Experience',
        text: 'From first enquiry to handover, buyers get concierge-level support.',
        icon: Gem,
    },
    {
        title: 'Transparent Deals',
        text: 'Clear pricing, no hidden steps, and financing guidance when needed.',
        icon: BadgeCheck,
    },
];

const processSteps = [
    {
        title: 'Browse & Shortlist',
        text: 'Use detailed filters to narrow down make, model, body type, and budget instantly.',
        icon: Sparkles,
    },
    {
        title: 'Talk to Our Team',
        text: 'Get transparent answers on condition, history, and ownership options.',
        icon: Users,
    },
    {
        title: 'Inspect & Drive Away',
        text: 'Book a visit, verify details, and complete your purchase with confidence.',
        icon: Wrench,
    },
];
</script>

<template>
    <Head title="About" />

    <section class="mx-auto max-w-7xl px-4 py-16 sm:py-20">
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[radial-gradient(circle_at_top,#303641_0%,#171717_55%,#111111_100%)] p-8 text-center sm:p-12">
            <div class="pointer-events-none absolute -top-18 -left-16 h-56 w-56 rounded-full bg-brand-400/20 blur-3xl" />
            <div class="pointer-events-none absolute -right-10 -bottom-16 h-64 w-64 rounded-full bg-sky-500/15 blur-3xl" />
            <p class="text-xs tracking-[0.32em] text-[#9CA3AF] uppercase">Who We Are</p>
            <h1 class="mt-3 font-display text-4xl sm:text-5xl">About {{ page.props.settings?.site_name ?? 'AutoNest' }}</h1>
            <p class="mx-auto mt-5 max-w-3xl text-[#B4B6BC]">
                {{ page.props.settings?.site_tagline ?? 'Drive Your Dream' }}. We help drivers find high-quality cars with confidence through verified listings, clear communication, and reliable after-sale support.
            </p>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <article class="rounded-2xl border border-white/10 bg-[#181818] p-5 text-center">
                <p class="text-xs text-[#9CA3AF]">Happy Buyers</p>
                <p class="mt-2 text-3xl font-semibold text-brand-400">{{ teamStats.happy_buyers }}</p>
            </article>
            <article class="rounded-2xl border border-white/10 bg-[#181818] p-5 text-center">
                <p class="text-xs text-[#9CA3AF]">Cars Delivered</p>
                <p class="mt-2 text-3xl font-semibold text-brand-400">{{ teamStats.cars_delivered }}</p>
            </article>
            <article class="rounded-2xl border border-white/10 bg-[#181818] p-5 text-center">
                <p class="text-xs text-[#9CA3AF]">Dealer Rating</p>
                <p class="mt-2 text-3xl font-semibold text-brand-400">{{ teamStats.dealer_rating }}</p>
            </article>
        </div>

        <div class="mt-10 grid gap-8 lg:grid-cols-2 lg:items-center">
            <div class="space-y-4 rounded-2xl border border-white/10 bg-[#1A1A1A] p-6">
                <h2 class="font-display text-3xl">Our Story</h2>
                <p class="text-[#D1D5DB]">AutoNest was built for drivers who wanted a better way to buy cars: less pressure, more clarity, and smarter choices.</p>
                <p class="text-[#D1D5DB]">We combine curated inventory with fast digital support so customers can compare, enquire, and decide without guesswork.</p>
                <p class="text-[#D1D5DB]">Our team values long-term relationships, which means every enquiry is treated like the start of a future partnership, not a one-time sale.</p>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <article
                    v-for="(car, index) in aboutCars.slice(0, 4)"
                    :key="`about-top-${car.id}`"
                    class="group overflow-hidden rounded-xl border border-white/10"
                    :class="index === 0 ? 'col-span-2' : ''"
                >
                    <img :src="assetUrl(car.images?.[0])" :alt="car.title" class="aspect-[16/10] w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                    <div class="border-t border-white/10 bg-[#151515] px-3 py-2">
                        <p class="truncate text-sm font-semibold">{{ car.title }}</p>
                    </div>
                </article>
                <article v-if="aboutCars.length === 0" class="col-span-2 overflow-hidden rounded-xl border border-white/10">
                    <img src="/images/car-placeholder.svg" alt="AutoNest showroom" class="aspect-[16/10] w-full object-cover" />
                </article>
            </div>
        </div>

        <div class="mt-10 grid gap-4 md:grid-cols-3">
            <article v-for="item in highlights" :key="item.title" class="rounded-2xl border border-white/10 bg-[#1A1A1A] p-5">
                <component :is="item.icon" class="h-6 w-6 text-brand-400" />
                <p class="mt-3 font-semibold">{{ item.title }}</p>
                <p class="mt-2 text-sm text-[#9CA3AF]">{{ item.text }}</p>
            </article>
        </div>

        <div class="mt-10 rounded-2xl border border-white/10 bg-[#151515] p-6 sm:p-8">
            <h3 class="font-display text-3xl">How We Help You Buy Better</h3>
            <div class="mt-5 grid gap-4 md:grid-cols-3">
                <article v-for="step in processSteps" :key="step.title" class="rounded-xl border border-white/10 bg-[#1D1D1D] p-4">
                    <component :is="step.icon" class="h-5 w-5 text-brand-400" />
                    <p class="mt-2 font-semibold">{{ step.title }}</p>
                    <p class="mt-2 text-sm text-[#9CA3AF]">{{ step.text }}</p>
                </article>
            </div>
        </div>

        <div class="mt-10">
            <h3 class="font-display text-3xl">Real Cars, Real Inventory</h3>
            <p class="mt-2 text-sm text-[#9CA3AF]">A quick look at vehicles currently listed on {{ page.props.settings?.site_name ?? 'AutoNest' }}.</p>
            <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <article v-for="car in aboutCars" :key="`about-grid-${car.id}`" class="overflow-hidden rounded-2xl border border-white/10 bg-[#171717]">
                    <img :src="assetUrl(car.images?.[0])" :alt="car.title" class="aspect-[4/3] w-full object-cover" />
                    <div class="p-4">
                        <p class="truncate font-semibold">{{ car.title }}</p>
                        <p class="mt-1 text-sm text-[#9CA3AF]">{{ car.year }} · {{ car.make }} {{ car.model }}</p>
                    </div>
                </article>
            </div>
        </div>

        <div class="mt-10 rounded-2xl border border-brand-400/30 bg-[#1A1A1A] p-8 text-center">
            <h3 class="font-display text-3xl">Ready to find your next car?</h3>
            <div class="mt-5 flex flex-wrap justify-center gap-3">
                <Link :href="carsIndex()" class="rounded bg-brand-400 px-5 py-3 font-semibold text-black">Browse Cars</Link>
                <Link :href="contactIndex()" class="rounded border border-brand-400 px-5 py-3 text-brand-400">Contact Our Team</Link>
            </div>
        </div>
    </section>
</template>

