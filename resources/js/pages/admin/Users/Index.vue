<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ShieldCheck, Users } from 'lucide-vue-next';
import EmptyState from '@/components/EmptyState.vue';
import PageContainer from '@/components/PageContainer.vue';
import PageHeader from '@/components/PageHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as usersIndex } from '@/routes/users';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: usersIndex(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Users" />

        <PageContainer>
            <PageHeader
                title="Users management"
                description="This admin-only area will become the canonical user-management module for the boilerplate."
            >
                <template #eyebrow>
                    <div class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-medium tracking-[0.2em] text-emerald-900 uppercase dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-100">
                        <Users class="size-3.5" />
                        Admin only
                    </div>
                </template>
            </PageHeader>

            <EmptyState
                title="Users CRUD is the next module"
                description="This placeholder proves the route, sidebar visibility, and permission checks are already active. The next step is building the full user management screens with role assignment."
                :icon="ShieldCheck"
            />
        </PageContainer>
    </AppLayout>
</template>
