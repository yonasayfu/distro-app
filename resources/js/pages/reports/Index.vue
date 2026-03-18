<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import ResourcePagination from '@/components/admin/ResourcePagination.vue';
import ResourceTable from '@/components/admin/ResourceTable.vue';
import ResourceToolbar from '@/components/admin/ResourceToolbar.vue';
import StatusBadge from '@/components/admin/StatusBadge.vue';
import PageContainer from '@/components/PageContainer.vue';
import PageHeader from '@/components/PageHeader.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as reportsIndex } from '@/routes/reports';
import { csv as reportsPagesCsv } from '@/routes/reports/pages';
import type { BreadcrumbItem, PaginatedResource, ResourceFilters } from '@/types';

type ReportRow = {
    id: number;
    title: string;
    slug: string;
    status: string;
    statusLabel: string;
    statusTone: string;
    isDeleted: boolean;
    deletedAt: string | null;
    updatedAt: string | null;
};

type Props = {
    pageReport: PaginatedResource<ReportRow>;
    filters: ResourceFilters & {
        status: string;
        trashed: string;
    };
    statusOptions: Array<{
        value: string;
        label: string;
    }>;
};

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Reports',
        href: reportsIndex(),
    },
];

const search = ref(props.filters.search);
const status = ref(props.filters.status);
const trashed = ref(props.filters.trashed);

const submitFilters = (): void => {
    router.get(
        reportsIndex.url({
            query: {
                search: search.value || undefined,
                status: status.value || undefined,
                trashed: trashed.value || undefined,
            },
        }),
        {},
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        },
    );
};

const exportUrl = computed(() =>
    reportsPagesCsv.url({
        query: {
            search: search.value || undefined,
            status: status.value || undefined,
            trashed: trashed.value || undefined,
        },
    }),
);

const resetFilters = (): void => {
    search.value = '';
    status.value = '';
    trashed.value = '';
    submitFilters();
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Reports" />

        <PageContainer>
            <PageHeader
                title="Reports"
                description="Filter one shared report surface, then export the current slice without building a custom report flow for every module."
            >
                <template #actions>
                    <Button as-child>
                        <a :href="exportUrl">Export current CSV</a>
                    </Button>
                </template>
            </PageHeader>

            <ResourceToolbar
                v-model:search="search"
                search-placeholder="Search pages by title, slug, or excerpt"
                @submit="submitFilters"
                @reset="resetFilters"
            >
                <template #actions>
                    <div class="flex flex-wrap items-center gap-2">
                        <select v-model="status" class="h-9 rounded-md border border-input bg-background px-3 text-sm" @change="submitFilters">
                            <option value="">All statuses</option>
                            <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </option>
                        </select>
                        <select v-model="trashed" class="h-9 rounded-md border border-input bg-background px-3 text-sm" @change="submitFilters">
                            <option value="">Active only</option>
                            <option value="with">With deleted</option>
                            <option value="only">Deleted only</option>
                        </select>
                    </div>
                </template>
            </ResourceToolbar>

            <ResourceTable
                :has-results="pageReport.data.length > 0"
                empty-title="No report rows found"
                empty-description="Try a broader filter selection."
            >
                <template #head>
                    <tr class="text-left text-xs tracking-wide text-muted-foreground uppercase">
                        <th class="px-4 py-3 font-medium">Page</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Deleted</th>
                        <th class="px-4 py-3 font-medium">Updated</th>
                    </tr>
                </template>

                <template #body>
                    <tr v-for="row in pageReport.data" :key="row.id">
                        <td class="px-4 py-4">
                            <div class="font-medium text-foreground">{{ row.title }}</div>
                            <div class="text-sm text-muted-foreground">/{{ row.slug }}</div>
                        </td>
                        <td class="px-4 py-4">
                            <StatusBadge :label="row.statusLabel" :tone="row.statusTone" />
                        </td>
                        <td class="px-4 py-4 text-sm text-muted-foreground">
                            {{ row.isDeleted ? 'Yes' : 'No' }}
                        </td>
                        <td class="px-4 py-4 text-sm text-muted-foreground">
                            {{ row.updatedAt ? new Date(row.updatedAt).toLocaleDateString() : 'N/A' }}
                        </td>
                    </tr>
                </template>
            </ResourceTable>

            <ResourcePagination :resource="pageReport" />
        </PageContainer>
    </AppLayout>
</template>
