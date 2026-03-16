<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Bell } from 'lucide-vue-next';
import EmptyState from '@/components/EmptyState.vue';
import PageContainer from '@/components/PageContainer.vue';
import PageHeader from '@/components/PageHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as notificationsIndex } from '@/routes/notifications';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Notifications',
        href: notificationsIndex(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Notifications" />

        <PageContainer>
            <PageHeader
                title="Notifications"
                description="This module is now permission-aware so different roles can visibly receive different navigation."
            />

            <EmptyState
                title="Notification center comes next"
                description="The route and sidebar permission are already in place. Later phases will add unread counts, feeds, and mark-read actions."
                :icon="Bell"
            />
        </PageContainer>
    </AppLayout>
</template>
