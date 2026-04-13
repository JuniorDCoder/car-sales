<script setup>
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

watch(allImages, () => {
    activeIndex.value = 0;
});
</script>

<template>
    <div class="space-y-3">
        <img :src="assetUrl(allImages[activeIndex])" alt="Car image" class="aspect-[16/9] w-full rounded-2xl object-cover" />
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

