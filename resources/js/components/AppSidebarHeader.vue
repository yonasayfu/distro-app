<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Bell, MoonStar, Settings2, SunMedium } from 'lucide-vue-next';
import { computed } from 'vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { useAppearance } from '@/composables/useAppearance';
import { index as notificationsIndex } from '@/routes/notifications';
import { edit as editProfile } from '@/routes/profile';
import type { Auth, BreadcrumbItem } from '@/types';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const { resolvedAppearance, updateAppearance } = useAppearance();
const page = usePage();
const auth = computed(() => page.props.auth as Auth);

const toggleAppearance = (): void => {
    updateAppearance(resolvedAppearance.value === 'dark' ? 'light' : 'dark');
};
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/70 bg-background/80 px-6 backdrop-blur transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex min-w-0 items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>
        <div class="ml-auto flex items-center gap-2">
            <Button
                v-if="auth.can.viewNotifications"
                variant="ghost"
                size="sm"
                class="rounded-full"
                as-child
            >
                <Link :href="notificationsIndex()">
                    <Bell class="size-4" />
                    <span>Notifications</span>
                    <Badge
                        v-if="auth.notificationCount > 0"
                        variant="secondary"
                        class="ml-1"
                    >
                        {{ auth.notificationCount }}
                    </Badge>
                </Link>
            </Button>
            <Button
                variant="ghost"
                size="icon-sm"
                class="rounded-full"
                @click="toggleAppearance"
            >
                <SunMedium
                    v-if="resolvedAppearance === 'dark'"
                    class="size-4"
                />
                <MoonStar v-else class="size-4" />
                <span class="sr-only">Toggle appearance</span>
            </Button>
            <Button variant="ghost" size="sm" class="rounded-full" as-child>
                <Link :href="editProfile()">
                    <Settings2 class="size-4" />
                    Settings
                </Link>
            </Button>
        </div>
    </header>
</template>
