<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarSeparator,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavGroup } from '@/types';

defineProps<{
    items: NavGroup[];
}>();

const { isCurrentUrl } = useCurrentUrl();
</script>

<template>
    <template
        v-for="(group, index) in items"
        :key="group.title"
    >
        <SidebarSeparator
            v-if="index > 0"
            class="mx-2 my-3"
        />
        <SidebarGroup class="px-2 py-0">
            <SidebarGroupLabel>{{ group.title }}</SidebarGroupLabel>
            <SidebarMenu>
                <SidebarMenuItem
                    v-for="item in group.items"
                    :key="`${group.title}-${item.title}`"
                >
                    <SidebarMenuButton
                        as-child
                        :is-active="isCurrentUrl(item.href)"
                        :tooltip="item.title"
                    >
                        <Link :href="item.href">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroup>
    </template>
</template>
