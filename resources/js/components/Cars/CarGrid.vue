<script setup lang="ts">
import CarCard from '@/components/Cars/CarCard.vue';

const props = withDefaults(defineProps<{
    cars?: Array<Record<string, any>>;
    showWhatsapp?: boolean;
}>(), {
    cars: () => [],
    showWhatsapp: true,
});
</script>

<template>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <CarCard v-for="car in props.cars" :key="car.id" :car="car" :show-whatsapp="props.showWhatsapp" />
    </div>
</template>

