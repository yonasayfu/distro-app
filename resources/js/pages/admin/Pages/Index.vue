<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ExternalLink, FileText, Plus, SquarePen, Trash2 } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import ActionIconLink from '@/components/admin/ActionIconLink.vue';
import ConfirmActionDialog from '@/components/admin/ConfirmActionDialog.vue';
import ResourcePagination from '@/components/admin/ResourcePagination.vue';
import ResourceTable from '@/components/admin/ResourceTable.vue';
import ResourceToolbar from '@/components/admin/ResourceToolbar.vue';
import PageContainer from '@/components/PageContainer.vue';
import PageHeader from '@/components/PageHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { create as createPage, destroy as destroyPage, edit as editPage, index as pagesIndex } from '@/routes/pages';
import type { Auth, BreadcrumbItem, ManagedPage, PaginatedResource, ResourceFilters } from '@/types';

type Props = {
    pages: PaginatedResource<ManagedPage>;
    filters: ResourceFilters;
};

const props = defineProps<Props>();
const page = usePage();
const auth = computed(() => page.props.auth as Auth);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pages',
        href: pagesIndex(),
    },
];

const search = ref(props.filters.search);

const submitSearch = (): void => {
    router.get(
        pagesIndex.url({
            query: {
                search: search.value || undefined,
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

const resetSearch = (): void => {
    search.value = '';
    submitSearch();
};

const deleteSelectedPage = (page: ManagedPage): void => {
    router.delete(destroyPage(page.id).url, {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Pages" />

        <PageContainer>
            <PageHeader
                title="Pages"
                description="Manage guest-facing content with one neutral content model that supports drafts, live slugs, and SEO basics."
            >
                <template #eyebrow>
                    <div class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-medium tracking-[0.2em] text-amber-900 uppercase dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100">
                        <FileText class="size-3.5" />
                        Public content
                    </div>
                </template>
                <template #actions>
                    <Button v-if="auth.permissions.includes('pages.create')" as-child>
                        <Link :href="createPage()">
                            <Plus class="size-4" />
                            Create page
                        </Link>
                    </Button>
                </template>
            </PageHeader>

            <ResourceToolbar
                v-model:search="search"
                search-placeholder="Search pages by title, slug, or excerpt"
                @submit="submitSearch"
                @reset="resetSearch"
            />

            <ResourceTable
                :has-results="pages.data.length > 0"
                empty-title="No pages found"
                empty-description="Create the first public page or widen the current search."
                :empty-icon="FileText"
            >
                <template #head>
                    <tr class="text-left text-xs tracking-wide text-muted-foreground uppercase">
                        <th class="px-4 py-3 font-medium">Page</th>
                        <th class="px-4 py-3 font-medium">Slug</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Updated</th>
                        <th class="px-4 py-3 font-medium text-right">Actions</th>
                    </tr>
                </template>

                <template #body>
                    <tr v-for="page in pages.data" :key="page.id" class="align-top">
                        <td class="px-4 py-4">
                            <div class="space-y-1">
                                <div class="font-medium text-foreground">{{ page.title }}</div>
                                <p class="max-w-xl text-sm text-muted-foreground">
                                    {{ page.excerpt || 'No excerpt added yet.' }}
                                </p>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-muted-foreground">
                            /{{ page.slug }}
                        </td>
                        <td class="px-4 py-4">
                            <Badge :variant="page.isPublished ? 'secondary' : 'outline'">
                                {{ page.isPublished ? 'Published' : 'Draft' }}
                            </Badge>
                        </td>
                        <td class="px-4 py-4 text-sm text-muted-foreground">
                            {{ page.updatedAt ? new Date(page.updatedAt).toLocaleDateString() : 'N/A' }}
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <ActionIconLink
                                    v-if="page.publicUrl"
                                    :href="page.publicUrl"
                                    label="Open live page"
                                    :icon="ExternalLink"
                                />
                                <ActionIconLink
                                    v-if="auth.permissions.includes('pages.update')"
                                    :href="editPage(page.id)"
                                    label="Edit page"
                                    :icon="SquarePen"
                                />

                                <ConfirmActionDialog
                                    v-if="auth.permissions.includes('pages.delete')"
                                    title="Delete page"
                                    :description="`Delete ${page.title}? This removes the page record and its public slug.`"
                                    confirm-label="Delete page"
                                    @confirm="deleteSelectedPage(page)"
                                >
                                    <template #trigger>
                                        <Button
                                            variant="ghost"
                                            size="icon-sm"
                                            class="rounded-full text-destructive"
                                        >
                                            <Trash2 class="size-4" />
                                        </Button>
                                    </template>
                                </ConfirmActionDialog>
                            </div>
                        </td>
                    </tr>
                </template>
            </ResourceTable>

            <ResourcePagination :resource="pages" />
        </PageContainer>
    </AppLayout>
</template>
