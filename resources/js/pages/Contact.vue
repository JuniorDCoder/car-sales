<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Clock3, Facebook, Instagram, Mail, MapPin, MessageCircle, Phone } from 'lucide-vue-next';
import { store as contactStore } from '@/routes/contact';

type SharedSettings = {
    contact_email?: string;
    whatsapp_enabled?: boolean;
    whatsapp_number?: string;
    whatsapp_message?: string;
    address?: string;
    facebook_url?: string;
    instagram_url?: string;
    site_name?: string;
};

const page = usePage<{ settings?: SharedSettings }>();

const settings = computed(() => {
    return {
        contact_email: page.props.settings?.contact_email || 'info@autonest.com',
        whatsapp_enabled: page.props.settings?.whatsapp_enabled ?? true,
        whatsapp_number: page.props.settings?.whatsapp_number || '',
        whatsapp_message: page.props.settings?.whatsapp_message || 'Hello, I would like to know more about your cars.',
        address: page.props.settings?.address || 'Contact address is being updated.',
        facebook_url: page.props.settings?.facebook_url || '',
        instagram_url: page.props.settings?.instagram_url || '',
        site_name: page.props.settings?.site_name || 'AutoNest',
    };
});

const form = useForm({
    name: '',
    email: '',
    phone: '',
    message: '',
});

function submit() {
    form.post(contactStore().url, {
        preserveScroll: true,
    });
}

function whatsappUrl() {
    if (!settings.value.whatsapp_enabled) {
        return '';
    }

    const phone = settings.value.whatsapp_number.toString().replace(/[^\d]/g, '');
    const message = encodeURIComponent(settings.value.whatsapp_message);

    if (!phone) {
        return '';
    }

    return `https://wa.me/${phone}?text=${message}`;
}
</script>

<template>
    <Head title="Contact" />

    <section class="mx-auto max-w-6xl px-4 py-16">
        <div class="rounded-3xl border border-white/10 bg-[radial-gradient(circle_at_top,#2e2e2e_0%,#171717_45%,#121212_100%)] p-8 sm:p-10">
            <p class="text-xs tracking-[0.3em] text-[#9CA3AF] uppercase">Get In Touch</p>
            <h1 class="mt-2 font-display text-4xl sm:text-5xl">Contact {{ settings.site_name }}</h1>
            <p class="mt-3 max-w-3xl text-[#B8BBC2]">Have a question about a listing, trade-in, or financing? Send us a message and our team will respond with clear next steps.</p>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2">
            <form class="space-y-3 rounded-2xl border border-white/10 bg-[#1A1A1A] p-6" @submit.prevent="submit">
                <h2 class="font-display text-3xl">Send a Message</h2>
                <p class="text-sm text-[#9CA3AF]">The fastest way to get a tailored recommendation from our team.</p>
                <input v-model="form.name" class="w-full rounded border border-white/10 bg-[#111] px-3 py-2" placeholder="Name" />
                <p v-if="form.errors.name" class="text-sm text-red-400">{{ form.errors.name }}</p>
                <input v-model="form.email" class="w-full rounded border border-white/10 bg-[#111] px-3 py-2" placeholder="Email" />
                <p v-if="form.errors.email" class="text-sm text-red-400">{{ form.errors.email }}</p>
                <input v-model="form.phone" class="w-full rounded border border-white/10 bg-[#111] px-3 py-2" placeholder="Phone" />
                <p v-if="form.errors.phone" class="text-sm text-red-400">{{ form.errors.phone }}</p>
                <textarea v-model="form.message" rows="5" class="w-full rounded border border-white/10 bg-[#111] px-3 py-2" placeholder="Message" />
                <p v-if="form.errors.message" class="text-sm text-red-400">{{ form.errors.message }}</p>
                <button class="rounded bg-brand-400 px-4 py-2 font-semibold text-black" :disabled="form.processing">Send Message</button>
            </form>

            <aside class="space-y-4 rounded-2xl border border-white/10 bg-[#1A1A1A] p-6 text-[#D1D5DB]">
                <h2 class="font-display text-3xl">Dealer Information</h2>

                <p class="inline-flex items-center gap-2"><Mail class="h-4 w-4 text-brand-400" />{{ settings.contact_email }}</p>

                <a v-if="whatsappUrl()" :href="whatsappUrl()" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-green-400">
                    <MessageCircle class="h-4 w-4" />{{ settings.whatsapp_number }}
                </a>
                <p v-else class="inline-flex items-center gap-2 text-[#9CA3AF]"><Phone class="h-4 w-4" />WhatsApp number not set yet</p>

                <p class="inline-flex items-start gap-2"><MapPin class="mt-1 h-4 w-4 text-brand-400" />{{ settings.address }}</p>
                <p class="inline-flex items-center gap-2 text-sm text-[#9CA3AF]"><Clock3 class="h-4 w-4 text-brand-400" />Response window: Mon-Sat, 9AM-6PM</p>

                <div class="border-t border-white/10 pt-4">
                    <p class="mb-2 text-sm text-[#9CA3AF]">Follow us</p>
                    <div class="flex items-center gap-2">
                        <a v-if="settings.facebook_url" :href="settings.facebook_url" target="_blank" rel="noopener noreferrer" class="rounded border border-white/10 p-2 hover:text-brand-400">
                            <Facebook class="h-4 w-4" />
                        </a>
                        <a v-if="settings.instagram_url" :href="settings.instagram_url" target="_blank" rel="noopener noreferrer" class="rounded border border-white/10 p-2 hover:text-brand-400">
                            <Instagram class="h-4 w-4" />
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </section>
</template>

