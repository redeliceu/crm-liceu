<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
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
import { dashboard, excel, statistics, template, list, analitics} from '@/routes';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { DollarSign, Folder, Headset, LayoutGrid, User } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import user from '@/routes/user';


const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Gastos Sellflux',
        href: excel(),
        icon: DollarSign,
    },
    {
        title: 'Estatísticas Atendimentos',
        href: statistics(),
        icon: Headset,
    },

    {
        title: 'Usúarios',
        href: user.list(),
        icon: User,
    },

    {
        title: 'Template',
        icon: Folder,
        children: [
            {
                title: 'Templates',
                href: list(),
            },
            {
                title: 'Adicionar Template',
                href: template(),
            },
            {
                title: 'Analitics',
                href: analitics(),
            }

        ],
    },






];

const footerNavItems: NavItem[] = [
    /*{
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    }, */
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
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
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
