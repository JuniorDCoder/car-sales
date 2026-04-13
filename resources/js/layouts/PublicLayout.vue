<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Facebook, Instagram, Menu, ShieldCheck, X } from 'lucide-vue-next';
import { about, home } from '@/routes';
import { index as carsIndex } from '@/routes/cars';
import { index as contactIndex } from '@/routes/contact';
import admin from '@/routes/admin';
import FlashMessage from '@/components/UI/FlashMessage.vue';
import WhatsAppButton from '@/components/WhatsAppButton.vue';

const page = usePage();

const settings = computed(() => page.props.settings ?? {});
const auth = computed(() => page.props.auth ?? {});
const navOpen = ref(false);

const currentUrl = computed(() => page.url ?? '/');

const navItems = computed(() => [
    { label: 'Home', href: home().url, active: currentUrl.value === '/' },
    { label: 'Cars', href: carsIndex().url, active: currentUrl.value.startsWith('/cars') },
    { label: 'About', href: about().url, active: currentUrl.value.startsWith('/about') },
    { label: 'Contact', href: contactIndex().url, active: currentUrl.value.startsWith('/contact') },
]);

const year = new Date().getFullYear();
</script>

<template>
    <div class="min-h-screen bg-[#0F0F0F] text-[#F5F5F5]">
        <FlashMessage />
        <header class="sticky top-0 z-40 border-b border-white/5 bg-black/80 backdrop-blur-md">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
                <Link :href="home()" class="font-display text-2xl font-semibold text-brand-400">
                    {{ settings.site_name ?? 'AutoNest' }}
                </Link>

                <nav class="hidden items-center gap-5 text-sm text-[#9CA3AF] md:flex">
                    <Link
                        v-for="item in navItems"
                        :key="item.label"
                        :href="item.href"
                        class="transition-all duration-300"
                        :class="item.active ? 'text-brand-400' : 'hover:text-brand-400'"
                    >
                        {{ item.label }}
                    </Link>
                    <Link
                        v-if="auth.user && auth.is_admin"
                        :href="admin.dashboard()"
                        class="inline-flex items-center gap-2 rounded border border-brand-400/50 px-3 py-1 text-brand-400"
                    >
                        <ShieldCheck class="h-4 w-4" />
                        Admin Panel
                    </Link>
                </nav>

                <button type="button" class="rounded-lg border border-white/10 p-2 md:hidden" @click="navOpen = !navOpen">
                    <Menu v-if="!navOpen" class="h-5 w-5" />
                    <X v-else class="h-5 w-5" />
                </button>
            </div>

            <div v-if="navOpen" class="border-t border-white/10 px-4 py-4 md:hidden">
                <nav class="space-y-2">
                    <Link
                        v-for="item in navItems"
                        :key="`mobile-${item.label}`"
                        :href="item.href"
                        class="block rounded-lg px-3 py-2 text-sm"
                        :class="item.active ? 'bg-brand-400/15 text-brand-400' : 'text-[#9CA3AF]'"
                        @click="navOpen = false"
                    >
                        {{ item.label }}
                    </Link>
                    <Link
                        v-if="auth.user && auth.is_admin"
                        :href="admin.dashboard()"
                        class="block rounded-lg border border-brand-400/50 px-3 py-2 text-sm text-brand-400"
                        @click="navOpen = false"
                    >
                        Admin Panel
                    </Link>
                </nav>
            </div>
        </header>

        <main>
            <slot />
        </main>

        <footer class="border-t border-white/5 bg-[#111]">
            <div class="mx-auto grid max-w-7xl gap-6 px-4 py-10 md:grid-cols-3">
                <div>
                    <h3 class="font-display text-xl text-brand-400">{{ settings.site_name ?? 'AutoNest' }}</h3>
                    <p class="mt-2 text-sm text-[#9CA3AF]">{{ settings.site_tagline ?? 'Drive Your Dream' }}</p>
                    <div class="mt-3 flex items-center gap-2">
                        <a v-if="settings.facebook_url" :href="settings.facebook_url" target="_blank" rel="noopener noreferrer" class="rounded border border-white/10 p-2 text-[#9CA3AF] hover:text-brand-400">
                            <Facebook class="h-4 w-4" />
                        </a>
                        <a v-if="settings.instagram_url" :href="settings.instagram_url" target="_blank" rel="noopener noreferrer" class="rounded border border-white/10 p-2 text-[#9CA3AF] hover:text-brand-400">
                            <Instagram class="h-4 w-4" />
                        </a>
                    </div>
                </div>
                <div class="text-sm text-[#9CA3AF]">
                    <p class="font-semibold text-white">Quick Links</p>
                    <div class="mt-2 space-y-1">
                        <Link :href="home()" class="block hover:text-brand-400">Home</Link>
                        <Link :href="carsIndex()" class="block hover:text-brand-400">Cars</Link>
                        <Link :href="about()" class="block hover:text-brand-400">About</Link>
                        <Link :href="contactIndex()" class="block hover:text-brand-400">Contact</Link>
                    </div>
                </div>
                <div class="text-sm text-[#9CA3AF]">
                    <p class="font-semibold text-white">Contact</p>
                    <p class="mt-2">{{ settings.contact_email }}</p>
                    <p>{{ settings.whatsapp_number }}</p>
                    <p>{{ settings.address }}</p>
                </div>
            </div>
            <div class="border-t border-white/5 px-4 py-4 text-center text-xs text-[#6B7280]">
                Copyright {{ year }} {{ settings.site_name ?? 'AutoNest' }}. All rights reserved.
            </div>
        </footer>

        <WhatsAppButton />
    </div>
</template>

