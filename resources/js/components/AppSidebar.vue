<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
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
import { appNavigation } from '@/navigation/app';
import { dashboard } from '@/routes';
import type { Auth, NavGroup } from '@/types';

const page = usePage();
const auth = computed(() => page.props.auth as Auth);
const roleSummary = computed(() => auth.value.roles[0] ?? 'No role assigned');

const mainNavigation = computed<NavGroup[]>(() =>
    appNavigation
        .map((group) => ({
            ...group,
            items: group.items.filter((item) => {
                if (!item.permission) {
                    return true;
                }

                return auth.value.permissions.includes(item.permission);
            }),
        }))
        .filter((group) => group.items.length > 0),
);
</script>

<template>
    <Sidebar
        collapsible="icon"
        variant="inset"
        class="border-r border-sidebar-border/70 bg-sidebar/90 backdrop-blur"
    >
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
            <div class="mt-3 px-2">
                <div class="rounded-xl border border-sidebar-border/70 bg-background/70 px-3 py-2">
                    <div class="text-[11px] uppercase tracking-[0.18em] text-muted-foreground">Access</div>
                    <div class="mt-1 text-sm font-medium text-foreground">{{ roleSummary }}</div>
                </div>
            </div>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavigation" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
