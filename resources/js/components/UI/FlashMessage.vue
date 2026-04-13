<script setup>
import { computed, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const visible = ref(false);

const successMessage = computed(() => page.props.flash?.success);
const errorMessage = computed(() => page.props.flash?.error);
const message = computed(() => successMessage.value || errorMessage.value);
const isError = computed(() => Boolean(errorMessage.value));

watch(message, (value) => {
    if (!value) {
        return;
    }

    visible.value = true;
    window.setTimeout(() => {
        visible.value = false;
    }, 4000);
});
</script>

<template>
    <transition enter-active-class="transition duration-300" enter-from-class="translate-y-2 opacity-0" leave-active-class="transition duration-200" leave-to-class="translate-y-2 opacity-0">
        <div
            v-if="visible && message"
            class="fixed top-20 right-5 z-[60] max-w-sm rounded-xl border px-4 py-3 text-sm shadow-xl shadow-black/40"
            :class="isError ? 'border-red-500/50 bg-red-500/10 text-red-300' : 'border-green-500/50 bg-green-500/10 text-green-300'"
        >
            <div class="flex items-start justify-between gap-4">
                <p>{{ message }}</p>
                <button type="button" class="text-xs text-white/80 hover:text-white" @click="visible = false">Dismiss</button>
            </div>
        </div>
    </transition>
</template>

