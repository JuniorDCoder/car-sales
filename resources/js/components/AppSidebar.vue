<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, Settings, ShieldCheck, UserCog } from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import admin from '@/routes/admin';
import adminCars from '@/routes/admin/cars';
import adminSettings from '@/routes/admin/settings';
import profile from '@/routes/profile';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Admin Dashboard',
        href: admin.dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Cars',
        href: adminCars.index(),
        icon: ShieldCheck,
    },
    {
        title: 'Site Settings',
        href: adminSettings.index(),
        icon: Settings,
    },
    {
        title: 'Profile Settings',
        href: profile.edit(),
        icon: UserCog,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="admin.dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
