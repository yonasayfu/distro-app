<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ScrollText } from 'lucide-vue-next';
import EmptyState from '@/components/EmptyState.vue';
import PageContainer from '@/components/PageContainer.vue';
import PageHeader from '@/components/PageHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as activityLogsIndex } from '@/routes/activity-logs';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Activity logs',
        href: activityLogsIndex(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Activity logs" />

        <PageContainer>
            <PageHeader
                title="Activity logs"
                description="This placeholder gives privileged roles a distinct navigation surface before the full audit module arrives."
            />

            <EmptyState
                title="Audit trail UI comes next"
                description="The permission, route, and sidebar entry already exist. Later phases will add real event logging and filters."
                :icon="ScrollText"
            />
        </PageContainer>
    </AppLayout>
</template>
