<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { MessageCircle } from 'lucide-vue-next';

const page = usePage();

const whatsappLink = computed(() => {
    const number = (page.props.settings?.whatsapp_number ?? '').toString().replace(/[^\d]/g, '');

    if (!number) {
        return null;
    }

    const message = encodeURIComponent(page.props.settings?.whatsapp_message ?? 'Hello');

    return `https://wa.me/${number}?text=${message}`;
});
</script>

<template>
    <a
        v-if="whatsappLink"
        :href="whatsappLink"
        target="_blank"
        rel="noopener noreferrer"
        class="fixed right-6 bottom-6 z-50 rounded-full bg-green-500 p-4 text-black shadow-lg shadow-black/40 transition-all duration-300 hover:scale-105"
        aria-label="Chat on WhatsApp"
    >
        <MessageCircle class="h-6 w-6" />
    </a>
</template>

