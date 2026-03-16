<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
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
import { appNavigation, appResourceLinks } from '@/navigation/app';
import { dashboard } from '@/routes';
import type { Auth, NavGroup } from '@/types';

const page = usePage();
const auth = computed(() => page.props.auth as Auth);
const roleSummary = computed(() => {
    if (auth.value.roles.length === 0) {
        return 'No role assigned';
    }

    return auth.value.roles.join(', ');
});

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
            <div class="mt-4 rounded-2xl border border-sidebar-border/70 bg-background/80 px-3 py-3 text-xs leading-5 text-muted-foreground">
                <div class="font-medium text-foreground">Current access</div>
                <div class="mt-1">{{ roleSummary }}</div>
                <div class="mt-2 text-[11px]">
                    Common items remain visible for everyone. Administration links appear only when the assigned role or permissions allow them.
                </div>
            </div>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavigation" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="appResourceLinks" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
