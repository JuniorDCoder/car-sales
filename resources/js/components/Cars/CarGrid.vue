<script setup>
import CarCard from '@/components/Cars/CarCard.vue';

defineProps({
    cars: {
        type: Array,
        default: () => [],
    },
    showWhatsapp: {
        type: Boolean,
        default: true,
    },
});
</script>

<template>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <CarCard v-for="car in cars" :key="car.id" :car="car" :show-whatsapp="showWhatsapp" />
    </div>
</template>

