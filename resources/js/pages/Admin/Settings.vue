<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import settingsRoutes from '@/routes/admin/settings';
import { useCurrency } from '@/composables/useCurrency';

type SiteSettingItem = {
    key: string;
    value: string | null;
    type: string;
    label: string;
};

const props = defineProps<{ settings: Record<string, SiteSettingItem[]> }>();

const initial: Record<string, string | boolean> = {};
Object.values(props.settings).forEach((group) => {
    group.forEach((setting: SiteSettingItem) => {
        if (setting.type === 'boolean') {
            initial[setting.key] = setting.value === '1';

            return;
        }

        initial[setting.key] = setting.value ?? '';
    });
});

const form = useForm<any>({ settings: initial });
const { formatPrice } = useCurrency();

function submit() {
    form.put(settingsRoutes.update().url, {
        preserveScroll: true,
    });
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
                        <label
                            v-else-if="setting.type === 'boolean'"
                            class="inline-flex items-center gap-2"
                        >
                            <input v-model="form.settings[setting.key]" type="checkbox" />
                            <span class="text-sm">Enabled</span>
                        </label>
                        <input
                            v-else
                            v-model="form.settings[setting.key]"
                            :type="setting.type === 'boolean' ? 'text' : setting.type"
                            class="w-full rounded border border-sidebar-border px-3 py-2"
                        />
                        <p v-if="form.errors[`settings.${setting.key}`]" class="text-sm text-red-500">
                            {{ form.errors[`settings.${setting.key}`] }}
                        </p>
                    </div>
                </div>
            </div>

            <p class="text-sm text-muted-foreground">Preview: {{ formatPrice(24500) }}</p>

            <button class="rounded bg-primary px-4 py-2 text-primary-foreground" :disabled="form.processing">Save All Settings</button>
        </form>
    </div>
</template>

