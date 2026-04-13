<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Facebook, Instagram, Mail, MapPin, MessageCircle } from 'lucide-vue-next';
import { store as contactStore } from '@/routes/contact';

const page = usePage();

const form = useForm({
    name: '',
    email: '',
    phone: '',
    message: '',
});

function submit() {
    form.post(contactStore(), {
        preserveScroll: true,
    });
}

function whatsappUrl() {
    const phone = (page.props.settings?.whatsapp_number ?? '').toString().replace(/[^\d]/g, '');
    const message = encodeURIComponent(page.props.settings?.whatsapp_message ?? 'Hello');

    return `https://wa.me/${phone}?text=${message}`;
}
</script>

<template>
    <Head title="Contact" />

    <section class="mx-auto grid max-w-6xl gap-8 px-4 py-16 md:grid-cols-2">
        <form class="space-y-3 rounded-2xl border border-white/10 bg-[#1A1A1A] p-6" @submit.prevent="submit">
            <h1 class="font-display text-4xl">Contact Us</h1>
            <p class="text-sm text-[#9CA3AF]">Send your enquiry and our team will respond quickly.</p>
            <input v-model="form.name" class="w-full rounded border border-white/10 bg-[#111] px-3 py-2" placeholder="Name" />
            <p v-if="form.errors.name" class="text-sm text-red-400">{{ form.errors.name }}</p>
            <input v-model="form.email" class="w-full rounded border border-white/10 bg-[#111] px-3 py-2" placeholder="Email" />
            <p v-if="form.errors.email" class="text-sm text-red-400">{{ form.errors.email }}</p>
            <input v-model="form.phone" class="w-full rounded border border-white/10 bg-[#111] px-3 py-2" placeholder="Phone" />
            <textarea v-model="form.message" rows="5" class="w-full rounded border border-white/10 bg-[#111] px-3 py-2" placeholder="Message" />
            <p v-if="form.errors.message" class="text-sm text-red-400">{{ form.errors.message }}</p>
            <button class="rounded bg-brand-400 px-4 py-2 font-semibold text-black" :disabled="form.processing">Send Message</button>
        </form>

        <aside class="space-y-4 rounded-2xl border border-white/10 bg-[#1A1A1A] p-6 text-[#D1D5DB]">
            <h2 class="font-display text-3xl">Dealer Information</h2>
            <p class="inline-flex items-center gap-2"><Mail class="h-4 w-4 text-brand-400" />{{ page.props.settings?.contact_email }}</p>
            <a :href="whatsappUrl()" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-green-400"><MessageCircle class="h-4 w-4" />{{ page.props.settings?.whatsapp_number }}</a>
            <p class="inline-flex items-start gap-2"><MapPin class="mt-1 h-4 w-4 text-brand-400" />{{ page.props.settings?.address }}</p>

            <div class="border-t border-white/10 pt-4">
                <p class="mb-2 text-sm text-[#9CA3AF]">Follow us</p>
                <div class="flex items-center gap-2">
                    <a v-if="page.props.settings?.facebook_url" :href="page.props.settings.facebook_url" target="_blank" rel="noopener noreferrer" class="rounded border border-white/10 p-2 hover:text-brand-400">
                        <Facebook class="h-4 w-4" />
                    </a>
                    <a v-if="page.props.settings?.instagram_url" :href="page.props.settings.instagram_url" target="_blank" rel="noopener noreferrer" class="rounded border border-white/10 p-2 hover:text-brand-400">
                        <Instagram class="h-4 w-4" />
                    </a>
                </div>
            </div>
        </aside>
    </section>
</template>

