<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

type Props = {
    href: NonNullable<InertiaLinkProps['href']>;
    label: string;
    icon: LucideIcon;
};

defineProps<Props>();
</script>

<template>
    <Button as-child variant="ghost" size="icon-sm" class="rounded-full">
        <Link :href="href" :aria-label="label" :title="label">
            <component :is="icon" class="size-4" />
        </Link>
    </Button>
</template>
