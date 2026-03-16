<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavGroup } from '@/types';

defineProps<{
    items: NavGroup[];
}>();

const { isCurrentUrl } = useCurrentUrl();
</script>

<template>
    <SidebarGroup
        v-for="group in items"
        :key="group.title"
        class="px-2 py-0 not-first:mt-4"
    >
        <SidebarGroupLabel>{{ group.title }}</SidebarGroupLabel>
        <p
            v-if="group.description"
            class="px-2 pb-2 text-xs leading-5 text-muted-foreground"
        >
            {{ group.description }}
        </p>
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
