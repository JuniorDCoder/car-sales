<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { assetUrl } from '@/composables/useAssetUrl';

const props = defineProps({
    images: {
        type: Array,
        default: () => [],
    },
});

const allImages = computed(() => (props.images?.length ? props.images : ['images/car-placeholder.svg']));
const activeIndex = ref(0);

const activeImage = computed(() => allImages.value[activeIndex.value] ?? allImages.value[0]);

function nextImage() {
    activeIndex.value = (activeIndex.value + 1) % allImages.value.length;
}

function previousImage() {
    activeIndex.value = (activeIndex.value - 1 + allImages.value.length) % allImages.value.length;
}

watch(allImages, () => {
    activeIndex.value = 0;
});
</script>

<template>
    <div class="space-y-3">
        <div class="relative overflow-hidden rounded-2xl border border-white/10">
            <img :src="assetUrl(activeImage)" alt="Car image" class="aspect-[16/9] w-full object-cover" />
            <div v-if="allImages.length > 1" class="absolute inset-x-0 bottom-0 flex items-center justify-between bg-black/50 px-3 py-2 text-xs text-white">
                <button type="button" class="rounded bg-black/60 px-2 py-1" @click="previousImage">Prev</button>
                <span>{{ activeIndex + 1 }} / {{ allImages.length }}</span>
                <button type="button" class="rounded bg-black/60 px-2 py-1" @click="nextImage">Next</button>
            </div>
        </div>
        <div class="grid grid-cols-4 gap-2">
            <button
                v-for="(image, index) in allImages"
                :key="`${image}-${index}`"
                type="button"
                class="overflow-hidden rounded-lg border transition-all duration-300"
                :class="activeIndex === index ? 'border-brand-400' : 'border-white/10 hover:border-brand-400/40'"
                @click="activeIndex = index"
            >
                <img :src="assetUrl(image)" alt="Car thumbnail" class="aspect-[4/3] w-full object-cover" />
            </button>
        </div>
    </div>
</template>

