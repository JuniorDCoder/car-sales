<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import settingsRoutes from '@/routes/admin/settings';
import { useCurrency } from '@/composables/useCurrency';

const props = defineProps({
    settings: { type: Object, required: true },
});

const initial = {};
Object.values(props.settings).forEach((group) => {
    group.forEach((setting) => {
        initial[setting.key] = setting.value ?? '';
    });
});

const form = useForm({ settings: initial });
const { formatPrice } = useCurrency();

function submit() {
    form.put(settingsRoutes.update());
}
</script>

<template>
    <Head title="Site Settings" />

    <div class="space-y-6 p-6">
        <h1 class="text-2xl font-semibold">Site Settings</h1>
        <form class="space-y-6 rounded-xl border border-sidebar-border p-4" @submit.prevent="submit">
            <div v-for="(group, groupName) in settings" :key="groupName" class="space-y-3">
                <h2 class="text-lg font-semibold capitalize">{{ groupName }}</h2>
                <div class="grid gap-3 md:grid-cols-2">
                    <div v-for="setting in group" :key="setting.key" class="space-y-2">
                        <label class="block text-sm">{{ setting.label }}</label>
                        <textarea
                            v-if="setting.type === 'textarea'"
                            v-model="form.settings[setting.key]"
                            rows="3"
                            class="w-full rounded border border-sidebar-border px-3 py-2"
                        />
                        <input
                            v-else
                            v-model="form.settings[setting.key]"
                            :type="setting.type === 'boolean' ? 'text' : setting.type"
                            class="w-full rounded border border-sidebar-border px-3 py-2"
                        />
                    </div>
                </div>
            </div>

            <p class="text-sm text-muted-foreground">Preview: {{ formatPrice(24500) }}</p>

            <button class="rounded bg-primary px-4 py-2 text-primary-foreground" :disabled="form.processing">Save All Settings</button>
        </form>
    </div>
</template>

