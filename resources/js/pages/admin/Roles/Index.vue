<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Shield, SlidersHorizontal } from 'lucide-vue-next';
import EmptyState from '@/components/EmptyState.vue';
import PageContainer from '@/components/PageContainer.vue';
import PageHeader from '@/components/PageHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as rolesIndex } from '@/routes/roles';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Roles',
        href: rolesIndex(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Roles" />

        <PageContainer>
            <PageHeader
                title="Roles and permission management"
                description="This admin-only area will control detailed access assignment for sidebar items, pages, CRUD operations, print, export, and future module actions."
            >
                <template #eyebrow>
                    <div class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-xs font-medium tracking-[0.2em] text-sky-900 uppercase dark:border-sky-500/30 dark:bg-sky-500/10 dark:text-sky-100">
                        <Shield class="size-3.5" />
                        RBAC control
                    </div>
                </template>
            </PageHeader>

            <EmptyState
                title="Detailed role management comes next"
                description="This placeholder proves the route and permission wiring. The next implementation step is the full role editor with permission checkboxes and dynamic access management."
                :icon="SlidersHorizontal"
            />
        </PageContainer>
    </AppLayout>
</template>
