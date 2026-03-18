<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, ExternalLink, FileText, Globe } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import FormSection from '@/components/admin/FormSection.vue';
import PageContainer from '@/components/PageContainer.vue';
import PageHeader from '@/components/PageHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Checkbox } from '@/components/ui/checkbox';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { edit as editPage, index as pagesIndex, update as updatePage } from '@/routes/pages';
import type { BreadcrumbItem, ManagedPage } from '@/types';

type Props = {
    page: ManagedPage;
};

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pages',
        href: pagesIndex(),
    },
    {
        title: props.page.title,
        href: editPage(props.page.id),
    },
];

const form = useForm({
    title: props.page.title,
    slug: props.page.slug,
    excerpt: props.page.excerpt ?? '',
    content: props.page.content ?? '',
    seo_title: props.page.seoTitle ?? '',
    seo_description: props.page.seoDescription ?? '',
    is_published: props.page.isPublished,
});

const submit = (): void => {
    form.put(updatePage(props.page.id).url);
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="`Edit ${page.title}`" />

        <PageContainer>
            <PageHeader
                :title="`Edit ${page.title}`"
                description="Keep the content, slug, and publish state aligned so the public website and admin workflow stay predictable."
            >
                <template #eyebrow>
                    <div class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-medium tracking-[0.2em] text-amber-900 uppercase dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100">
                        <Globe class="size-3.5" />
                        Page editor
                    </div>
                </template>
                <template #actions>
                    <div class="flex items-center gap-2">
                        <Badge :variant="page.isPublished ? 'secondary' : 'outline'">
                            {{ page.isPublished ? 'Published' : 'Draft' }}
                        </Badge>
                        <Button v-if="page.publicUrl" as-child variant="outline">
                            <a :href="page.publicUrl" target="_blank" rel="noopener noreferrer">
                                <ExternalLink class="size-4" />
                                View live
                            </a>
                        </Button>
                        <Button as-child variant="outline">
                            <Link :href="pagesIndex()">
                                <ArrowLeft class="size-4" />
                                Back to pages
                            </Link>
                        </Button>
                    </div>
                </template>
            </PageHeader>

            <form class="grid gap-6" @submit.prevent="submit">
                <FormSection>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="title">Title</Label>
                            <Input id="title" v-model="form.title" />
                            <InputError :message="form.errors.title" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="slug">Slug</Label>
                            <Input id="slug" v-model="form.slug" />
                            <InputError :message="form.errors.slug" />
                        </div>

                        <div class="grid gap-2 md:col-span-2">
                            <Label for="excerpt">Excerpt</Label>
                            <textarea
                                id="excerpt"
                                v-model="form.excerpt"
                                rows="3"
                                class="flex min-h-24 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none ring-offset-background placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]"
                            />
                            <InputError :message="form.errors.excerpt" />
                        </div>

                        <div class="grid gap-2 md:col-span-2">
                            <Label for="content">Content</Label>
                            <textarea
                                id="content"
                                v-model="form.content"
                                rows="14"
                                class="flex min-h-80 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none ring-offset-background placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]"
                            />
                            <InputError :message="form.errors.content" />
                        </div>
                    </div>
                </FormSection>

                <FormSection
                    title="Publishing and SEO"
                    description="Publishing exposes the page on its slug. Draft pages stay private even if someone guesses the URL."
                >
                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="seo_title">SEO title</Label>
                            <Input id="seo_title" v-model="form.seo_title" />
                            <InputError :message="form.errors.seo_title" />
                        </div>

                        <div class="grid gap-2 md:col-span-2">
                            <Label for="seo_description">SEO description</Label>
                            <textarea
                                id="seo_description"
                                v-model="form.seo_description"
                                rows="3"
                                class="flex min-h-24 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none ring-offset-background placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]"
                            />
                            <InputError :message="form.errors.seo_description" />
                        </div>
                    </div>

                    <label class="mt-6 flex items-start gap-3 rounded-2xl border border-border/70 bg-background/70 px-4 py-4">
                        <Checkbox
                            :checked="form.is_published"
                            @update:checked="form.is_published = $event === true"
                        />
                        <div class="min-w-0">
                            <div class="text-sm font-medium text-foreground">Published</div>
                            <p class="mt-1 text-sm leading-6 text-muted-foreground">
                                Toggle this off to hide the page from the public website without deleting its content.
                            </p>
                        </div>
                    </label>
                    <InputError :message="form.errors.is_published" />
                </FormSection>

                <div class="flex items-center justify-end gap-3">
                    <Button type="submit" :disabled="form.processing || !form.isDirty">
                        <FileText class="size-4" />
                        Save page
                    </Button>
                </div>
            </form>
        </PageContainer>
    </AppLayout>
</template>
