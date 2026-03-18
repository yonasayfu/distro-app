<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { BarChart3, FileSpreadsheet, FileText, Settings2 } from 'lucide-vue-next';
import { computed } from 'vue';
import RecentActivityPanel from '@/components/admin/RecentActivityPanel.vue';
import StatCard from '@/components/admin/StatCard.vue';
import PageContainer from '@/components/PageContainer.vue';
import PageHeader from '@/components/PageHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { edit as adminSettingsEdit } from '@/routes/admin-settings';
import { dashboard } from '@/routes';
import { importMethod as pagesImportIndex, index as pagesIndex } from '@/routes/pages';
import { index as reportsIndex } from '@/routes/reports';
import type { Auth, BreadcrumbItem } from '@/types';

type Metric = {
    key: string;
    label: string;
    value: number;
    description: string;
    tone: 'amber' | 'sky' | 'emerald' | 'violet';
};

type ActivityItem = {
    id: number;
    event: string;
    description: string;
    createdAt: string | null;
};

type Props = {
    metrics: Metric[];
    recentActivity: ActivityItem[];
    reportHighlights: {
        publishedPages: number;
        reviewPages: number;
        deletedPages: number;
        completedImports: number;
    };
};

defineProps<Props>();

const page = usePage();
const auth = computed(() => page.props.auth as Auth);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
    },
];

const quickLinks = computed(() => [
    {
        title: 'Reports',
        description: 'Open the filterable reporting surface and export the current page report.',
        href: reportsIndex(),
        icon: BarChart3,
        visible: auth.value.can.viewReports,
    },
    {
        title: 'Pages',
        description: 'Manage public content records and restore deleted pages when needed.',
        href: pagesIndex(),
        icon: FileText,
        visible: auth.value.can.managePages,
    },
    {
        title: 'Page import',
        description: 'Preview CSV imports before creating content in bulk.',
        href: pagesImportIndex(),
        icon: FileSpreadsheet,
        visible: auth.value.permissions.includes('pages.create'),
    },
    {
        title: 'Business settings',
        description: 'Adjust organization-level configuration and public-site values.',
        href: adminSettingsEdit(),
        icon: Settings2,
        visible: auth.value.can.manageSettings,
    },
].filter((item) => item.visible));
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <PageContainer class="bg-[radial-gradient(circle_at_top_left,#fef3c7,transparent_28%),radial-gradient(circle_at_top_right,#dbeafe,transparent_24%)] dark:bg-[radial-gradient(circle_at_top_left,rgba(245,158,11,0.12),transparent_28%),radial-gradient(circle_at_top_right,rgba(59,130,246,0.16),transparent_24%)]">
            <PageHeader
                title="Business starter workspace"
                description="This dashboard now acts like the shared reporting surface for the business-level starter, not a static placeholder page."
            >
                <template #actions>
                    <div v-if="quickLinks.length > 0" class="text-sm text-muted-foreground">
                        {{ quickLinks.length }} business surface{{ quickLinks.length === 1 ? '' : 's' }} available from your current access set.
                    </div>
                </template>
            </PageHeader>

            <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <StatCard
                    v-for="metric in metrics"
                    :key="metric.key"
                    :label="metric.label"
                    :value="metric.value"
                    :description="metric.description"
                    :tone="metric.tone"
                />
            </section>

            <section class="grid gap-4 xl:grid-cols-[1.3fr_1fr]">
                <RecentActivityPanel :items="recentActivity" />

                <div class="space-y-4">
                    <section class="rounded-[1.5rem] border border-border/70 bg-card/90 p-6 shadow-sm backdrop-blur">
                        <h2 class="text-lg font-semibold">Report highlights</h2>
                        <div class="mt-5 grid gap-3">
                            <div class="rounded-2xl border border-border/70 bg-background/80 px-4 py-4">
                                <div class="text-sm text-muted-foreground">Published pages</div>
                                <div class="mt-1 text-2xl font-semibold">{{ reportHighlights.publishedPages }}</div>
                            </div>
                            <div class="rounded-2xl border border-border/70 bg-background/80 px-4 py-4">
                                <div class="text-sm text-muted-foreground">Pages in review</div>
                                <div class="mt-1 text-2xl font-semibold">{{ reportHighlights.reviewPages }}</div>
                            </div>
                            <div class="rounded-2xl border border-border/70 bg-background/80 px-4 py-4">
                                <div class="text-sm text-muted-foreground">Deleted pages</div>
                                <div class="mt-1 text-2xl font-semibold">{{ reportHighlights.deletedPages }}</div>
                            </div>
                            <div class="rounded-2xl border border-border/70 bg-background/80 px-4 py-4">
                                <div class="text-sm text-muted-foreground">Completed imports</div>
                                <div class="mt-1 text-2xl font-semibold">{{ reportHighlights.completedImports }}</div>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-[1.5rem] border border-border/70 bg-card/90 p-6 shadow-sm backdrop-blur">
                        <h2 class="text-lg font-semibold">Quick access</h2>
                        <div class="mt-5 grid gap-3">
                            <Link
                                v-for="item in quickLinks"
                                :key="item.title"
                                :href="item.href"
                                class="rounded-2xl border border-border/70 bg-background/80 px-4 py-4 transition hover:border-foreground/20 hover:bg-background"
                            >
                                <div class="flex items-start gap-3">
                                    <component :is="item.icon" class="mt-0.5 size-4 shrink-0 text-muted-foreground" />
                                    <div>
                                        <div class="text-sm font-medium text-foreground">{{ item.title }}</div>
                                        <p class="mt-1 text-sm leading-6 text-muted-foreground">{{ item.description }}</p>
                                    </div>
                                </div>
                            </Link>

                            <div
                                v-if="quickLinks.length === 0"
                                class="rounded-2xl border border-dashed border-border/70 bg-background/50 px-4 py-6 text-sm text-muted-foreground"
                            >
                                No extra quick links are exposed for this role yet. The dashboard metrics and recent activity still reflect the shared business baseline.
                            </div>
                        </div>
                    </section>
                </div>
            </section>
        </PageContainer>
    </AppLayout>
</template>
