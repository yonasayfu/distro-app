<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, FileSpreadsheet, Upload } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import FormSection from '@/components/admin/FormSection.vue';
import PageContainer from '@/components/PageContainer.vue';
import PageHeader from '@/components/PageHeader.vue';
import StatusBadge from '@/components/admin/StatusBadge.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { importMethod as pagesImportIndex, index as pagesIndex } from '@/routes/pages';
import { preview as previewPageImport, store as runPageImport } from '@/routes/pages/import';
import type { BreadcrumbItem, ManagedImportRun, PageImportPreview } from '@/types';

type Props = {
    preview: PageImportPreview | null;
    importRuns: ManagedImportRun[];
};

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pages',
        href: pagesIndex(),
    },
    {
        title: 'Import',
        href: pagesImportIndex(),
    },
];

const previewForm = useForm<{
    file: File | null;
}>({
    file: null,
});

const runForm = useForm<{
    import_run_id: number | null;
}>({
    import_run_id: null,
});

const previewImport = (): void => {
    previewForm.post(previewPageImport().url, {
        forceFormData: true,
    });
};

const runImport = (importRunId: number): void => {
    runForm.import_run_id = importRunId;
    runForm.post(runPageImport().url);
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Import pages" />

        <PageContainer>
            <PageHeader
                title="Import pages"
                description="Preview CSV rows before you write them, then keep a small history of completed imports for later review."
            >
                <template #eyebrow>
                    <div class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-xs font-medium tracking-[0.2em] text-sky-900 uppercase dark:border-sky-500/30 dark:bg-sky-500/10 dark:text-sky-100">
                        <FileSpreadsheet class="size-3.5" />
                        Import baseline
                    </div>
                </template>
                <template #actions>
                    <Button as-child variant="outline">
                        <Link :href="pagesIndex()">
                            <ArrowLeft class="size-4" />
                            Back to pages
                        </Link>
                    </Button>
                </template>
            </PageHeader>

            <form class="grid gap-6" @submit.prevent="previewImport">
                <FormSection
                    title="Upload CSV"
                    description="Expected columns: title, slug, excerpt, content, seo_title, seo_description, status."
                >
                    <div class="grid gap-3">
                        <Input type="file" accept=".csv,text/csv" @input="previewForm.file = ($event.target as HTMLInputElement).files?.[0] ?? null" />
                        <InputError :message="previewForm.errors.file" />
                    </div>

                    <template #headerAction>
                        <Button type="submit" :disabled="previewForm.processing || !previewForm.file">
                            <Upload class="size-4" />
                            Preview import
                        </Button>
                    </template>
                </FormSection>
            </form>

            <FormSection
                v-if="preview"
                title="Preview"
                description="Only valid rows will import. Invalid rows must be corrected in the CSV and previewed again."
            >
                <div class="mb-5 grid gap-3 md:grid-cols-3">
                    <div class="rounded-2xl border border-border/70 bg-background/80 px-4 py-4 text-sm">
                        <div class="text-muted-foreground">Rows</div>
                        <div class="mt-1 text-xl font-semibold">{{ preview.summary.rows }}</div>
                    </div>
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-900 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-100">
                        <div>Valid rows</div>
                        <div class="mt-1 text-xl font-semibold">{{ preview.summary.validRows }}</div>
                    </div>
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm text-amber-900 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100">
                        <div>Invalid rows</div>
                        <div class="mt-1 text-xl font-semibold">{{ preview.summary.invalidRows }}</div>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-border/70">
                    <table class="min-w-full divide-y divide-border/60 text-sm">
                        <thead class="bg-muted/40">
                            <tr class="text-left">
                                <th class="px-4 py-3 font-medium">Line</th>
                                <th class="px-4 py-3 font-medium">Title</th>
                                <th class="px-4 py-3 font-medium">Slug</th>
                                <th class="px-4 py-3 font-medium">Status</th>
                                <th class="px-4 py-3 font-medium">Validation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/60">
                            <tr v-for="row in preview.rows" :key="row.line">
                                <td class="px-4 py-3">{{ row.line }}</td>
                                <td class="px-4 py-3">{{ row.title }}</td>
                                <td class="px-4 py-3 text-muted-foreground">/{{ row.slug }}</td>
                                <td class="px-4 py-3">
                                    <StatusBadge :label="row.status" :tone="row.status === 'published' ? 'published' : row.status === 'review' ? 'review' : row.status === 'archived' ? 'archived' : 'draft'" />
                                </td>
                                <td class="px-4 py-3">
                                    <div v-if="row.valid" class="text-emerald-700 dark:text-emerald-300">Valid</div>
                                    <ul v-else class="space-y-1 text-amber-700 dark:text-amber-300">
                                        <li v-for="error in row.errors" :key="error">{{ error }}</li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-5 flex justify-end">
                    <Button
                        :disabled="runForm.processing || preview.summary.invalidRows > 0"
                        @click="runImport(preview.importRunId)"
                    >
                        Import valid rows
                    </Button>
                </div>
            </FormSection>

            <FormSection
                title="Recent imports"
                description="This is the reusable import history baseline for later modules."
            >
                <div class="overflow-x-auto rounded-2xl border border-border/70">
                    <table class="min-w-full divide-y divide-border/60 text-sm">
                        <thead class="bg-muted/40">
                            <tr class="text-left">
                                <th class="px-4 py-3 font-medium">File</th>
                                <th class="px-4 py-3 font-medium">Status</th>
                                <th class="px-4 py-3 font-medium">Rows</th>
                                <th class="px-4 py-3 font-medium">Imported</th>
                                <th class="px-4 py-3 font-medium">Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/60">
                            <tr v-for="run in importRuns" :key="run.id">
                                <td class="px-4 py-3">{{ run.fileName }}</td>
                                <td class="px-4 py-3">{{ run.status }}</td>
                                <td class="px-4 py-3">{{ run.rowsCount }} / {{ run.validRowsCount }} valid</td>
                                <td class="px-4 py-3">{{ run.importedRowsCount }}</td>
                                <td class="px-4 py-3 text-muted-foreground">{{ run.createdAt ? new Date(run.createdAt).toLocaleString() : 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </FormSection>
        </PageContainer>
    </AppLayout>
</template>
