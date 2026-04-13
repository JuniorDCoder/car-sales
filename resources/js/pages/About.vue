<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { BadgeCheck, Gem, Shield } from 'lucide-vue-next';
import { index as carsIndex } from '@/routes/cars';
import { index as contactIndex } from '@/routes/contact';

const page = usePage();
</script>

<template>
    <Head title="About" />

    <section class="mx-auto max-w-7xl px-4 py-20">
        <div class="rounded-2xl border border-white/10 bg-[#1A1A1A] p-10 text-center">
            <h1 class="font-display text-5xl">About {{ page.props.settings?.site_name ?? 'AutoNest' }}</h1>
            <p class="mx-auto mt-5 max-w-3xl text-[#9CA3AF]">
                We help drivers discover premium vehicles with transparent pricing and responsive support.
            </p>
        </div>

        <div class="mt-10 grid gap-8 lg:grid-cols-2">
            <div class="space-y-4 rounded-2xl border border-white/10 bg-[#1A1A1A] p-6">
                <h2 class="font-display text-3xl">Our Story</h2>
                <p class="text-[#D1D5DB]">AutoNest was built for buyers who want quality inventory and a fast, friendly buying journey.</p>
                <p class="text-[#D1D5DB]">From first contact to final handover, our team focuses on trust, clarity, and long-term relationships.</p>
            </div>
            <img src="/images/car-placeholder.svg" alt="AutoNest showroom" class="aspect-16/10 w-full rounded-2xl object-cover" />
        </div>

        <div class="mt-10 grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-white/10 bg-[#1A1A1A] p-5"><Shield class="h-6 w-6 text-brand-400" /><p class="mt-3 font-semibold">Trusted Vehicles</p><p class="mt-2 text-sm text-[#9CA3AF]">Carefully inspected and verified inventory.</p></div>
            <div class="rounded-2xl border border-white/10 bg-[#1A1A1A] p-5"><Gem class="h-6 w-6 text-brand-400" /><p class="mt-3 font-semibold">Premium Experience</p><p class="mt-2 text-sm text-[#9CA3AF]">Luxury-focused service from start to finish.</p></div>
            <div class="rounded-2xl border border-white/10 bg-[#1A1A1A] p-5"><BadgeCheck class="h-6 w-6 text-brand-400" /><p class="mt-3 font-semibold">Transparent Deals</p><p class="mt-2 text-sm text-[#9CA3AF]">Fair pricing and clear communication.</p></div>
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

