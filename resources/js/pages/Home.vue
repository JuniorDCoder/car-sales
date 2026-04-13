<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { BadgeCheck, Banknote, Car, CircleDollarSign, Clock3, Handshake, MessageCircle, ShieldCheck, Sparkles, Wrench } from 'lucide-vue-next';
import { index as carsIndex, show as carsShow } from '@/routes/cars';
import { index as contactIndex } from '@/routes/contact';
import CarGrid from '@/components/Cars/CarGrid.vue';

const page = usePage();

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

const trustMetrics = [
    { label: 'Vehicles Listed Yearly', value: '1,200+' },
    { label: 'Customer Satisfaction', value: '98%' },
    { label: 'Average Delivery Time', value: '48 hrs' },
    { label: 'Financing Approval Support', value: '24/7' },
];

const buyingSteps = [
    {
        title: 'Pick Your Match',
        description: 'Browse curated inventory with transparent specs, pricing, and ownership details.',
        icon: Car,
    },
    {
        title: 'Book Inspection',
        description: 'Schedule virtual or in-person inspection with detailed walk-through and reporting.',
        icon: Wrench,
    },
    {
        title: 'Secure Financing',
        description: 'Get personalized finance plans with fast approval guidance from our advisors.',
        icon: Banknote,
    },
    {
        title: 'Drive Home Confidently',
        description: 'Complete paperwork quickly and enjoy smooth delivery with after-sales support.',
        icon: Handshake,
    },
];

const testimonials = [
    {
        quote: 'The team handled financing and delivery perfectly. The car was exactly as shown.',
        name: 'Melissa R.',
        role: 'Business Owner',
    },
    {
        quote: 'AutoNest gave me clear options with zero pressure. Fast, transparent, professional.',
        name: 'David K.',
        role: 'Software Engineer',
    },
    {
        quote: 'I found a clean SUV in two days. Their WhatsApp support was incredibly responsive.',
        name: 'Janet O.',
        role: 'Healthcare Professional',
    },
];

const faqs = [
    {
        question: 'Do you offer trade-ins?',
        answer: 'Yes. We evaluate your current vehicle and provide a fair market offer that can be applied to your next purchase.',
    },
    {
        question: 'Can I reserve a car remotely?',
        answer: 'Absolutely. You can reserve your preferred vehicle online while we complete verification and paperwork.',
    },
    {
        question: 'Do your cars come inspected?',
        answer: 'Every listing goes through quality and documentation checks before going live on AutoNest.',
    },
];
</script>

<template>
    <Head title="Home" />

    <section class="relative isolate overflow-hidden px-4 pt-22 pb-24 md:pt-30">
        <img
            src="https://images.unsplash.com/photo-1493238792000-8113da705763?auto=format&fit=crop&w=2200&q=80"
            alt="Luxury sports car in motion"
            class="animate-slow-pan absolute inset-0 h-full w-full scale-110 object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/60 to-black/45" />
        <div class="absolute -top-18 -right-14 h-72 w-72 rounded-full bg-brand-400/20 blur-3xl" />
        <div class="absolute bottom-0 -left-24 h-80 w-80 rounded-full bg-sky-500/20 blur-3xl" />

        <div class="relative mx-auto grid max-w-7xl gap-10 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-7">
                <p class="text-sm tracking-[0.35em] text-brand-400 uppercase">Premium Auto Marketplace</p>
                <h1 class="mt-4 animate-fade-in font-display text-5xl leading-tight font-semibold text-white md:text-7xl">
                    {{ heroHeadline }}
                </h1>
                <p class="mt-6 max-w-2xl text-lg text-[#D1D5DB]">
                    {{ heroSubheadline }}
                    <span class="block pt-3 text-base text-[#9CA3AF]">{{ page.props.settings?.site_name ?? 'AutoNest' }} brings trusted inventory, smart pricing, and concierge-level support into one seamless experience.</span>
                </p>
                <div class="mt-10 flex flex-wrap gap-3">
                    <Link :href="carsIndex()" class="rounded-xl bg-brand-400 px-6 py-3 font-semibold text-black transition-all duration-300 hover:bg-brand-500">
                        Browse Cars
                    </Link>
                    <Link :href="contactIndex()" class="rounded-xl border border-white/25 bg-white/5 px-6 py-3 font-semibold text-white transition-all duration-300 hover:bg-white/15">
                        Book Consultation
                    </Link>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="animate-float rounded-2xl border border-white/15 bg-white/10 p-6 backdrop-blur-md">
                    <h2 class="font-display text-2xl text-white">Why Buyers Choose AutoNest</h2>
                    <div class="mt-5 space-y-3 text-sm text-[#E5E7EB]">
                        <p class="flex items-center gap-2"><ShieldCheck class="h-4 w-4 text-brand-400" /> Verified cars and transparent documentation</p>
                        <p class="flex items-center gap-2"><Clock3 class="h-4 w-4 text-brand-400" /> Fast response via sales concierge support</p>
                        <p class="flex items-center gap-2"><Sparkles class="h-4 w-4 text-brand-400" /> Premium detailing before every handover</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="border-y border-white/5 bg-[#0F1628]">
        <div class="mx-auto grid max-w-7xl grid-cols-2 gap-5 px-4 py-10 md:grid-cols-4">
            <div v-for="item in trustMetrics" :key="item.label" class="rounded-xl border border-white/10 bg-white/5 px-4 py-5 text-center">
                <p class="text-3xl font-bold text-brand-400">{{ item.value }}</p>
                <p class="mt-2 text-sm text-[#A1AFC8]">{{ item.label }}</p>
            </div>
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

    <section class="mx-auto max-w-7xl px-4 py-14">
        <div class="mb-6 flex items-end justify-between">
            <h2 class="font-display text-3xl">How It Works</h2>
            <p class="text-sm text-[#9CA3AF]">A smooth process from search to keys.</p>
        </div>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article v-for="step in buyingSteps" :key="step.title" class="rounded-2xl border border-white/10 bg-[#141414] p-5">
                <component :is="step.icon" class="h-6 w-6 text-brand-400" />
                <p class="mt-3 font-semibold">{{ step.title }}</p>
                <p class="mt-2 text-sm text-[#9CA3AF]">{{ step.description }}</p>
            </article>
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
        <div class="grid gap-6 rounded-3xl border border-white/10 bg-gradient-to-r from-[#182036] via-[#1D2A46] to-[#12203A] p-8 lg:grid-cols-2">
            <div>
                <h2 class="font-display text-3xl">Flexible Financing & Trade-in</h2>
                <p class="mt-3 text-[#CBD5E1]">
                    Get tailored payment plans and trade-in support from our finance specialists.
                </p>
                <ul class="mt-5 space-y-3 text-sm text-[#E2E8F0]">
                    <li class="flex items-center gap-2"><BadgeCheck class="h-4 w-4 text-brand-400" /> Transparent monthly plan options</li>
                    <li class="flex items-center gap-2"><BadgeCheck class="h-4 w-4 text-brand-400" /> Fast pre-approval guidance</li>
                    <li class="flex items-center gap-2"><BadgeCheck class="h-4 w-4 text-brand-400" /> Trade-in valuation assistance</li>
                </ul>
            </div>
            <div class="rounded-2xl border border-white/15 bg-black/25 p-6">
                <p class="text-sm text-[#A5B4CC]">Support Highlights</p>
                <div class="mt-4 space-y-4">
                    <div class="rounded-xl border border-white/10 bg-white/5 p-4">
                        <p class="text-xs text-[#A5B4CC]">Approval Guidance</p>
                        <p class="mt-1 text-2xl font-semibold">Same Day</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-white/5 p-4">
                        <p class="text-xs text-[#A5B4CC]">Trade-In Evaluation</p>
                        <p class="mt-1 text-2xl font-semibold">Within 30 mins</p>
                    </div>
                </div>
            </div>
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
            <h2 class="font-display text-3xl">Customer Stories</h2>
            <p class="text-sm text-[#9CA3AF]">Trusted by growing families and professionals.</p>
        </div>
        <div class="grid gap-4 md:grid-cols-3">
            <article v-for="item in testimonials" :key="item.name" class="rounded-2xl border border-white/10 bg-[#161616] p-5">
                <p class="text-sm text-[#D1D5DB]">“{{ item.quote }}”</p>
                <p class="mt-4 font-semibold">{{ item.name }}</p>
                <p class="text-xs text-[#9CA3AF]">{{ item.role }}</p>
            </article>
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

    <section class="mx-auto max-w-5xl px-4 pt-8 pb-24">
        <h2 class="text-center font-display text-3xl">Frequently Asked Questions</h2>
        <div class="mt-8 space-y-4">
            <article v-for="faq in faqs" :key="faq.question" class="rounded-2xl border border-white/10 bg-[#161616] p-5">
                <p class="font-semibold">{{ faq.question }}</p>
                <p class="mt-2 text-sm text-[#9CA3AF]">{{ faq.answer }}</p>
            </article>
        </div>
    </section>
</template>

