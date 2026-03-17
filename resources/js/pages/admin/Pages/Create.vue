<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, FileText, Globe } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import PageContainer from '@/components/PageContainer.vue';
import PageHeader from '@/components/PageHeader.vue';
import { Checkbox } from '@/components/ui/checkbox';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { create as createPage, index as pagesIndex, store as storePage } from '@/routes/pages';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pages',
        href: pagesIndex(),
    },
    {
        title: 'Create',
        href: createPage(),
    },
];

const form = useForm({
    title: '',
    slug: '',
    excerpt: '',
    content: '',
    seo_title: '',
    seo_description: '',
    is_published: false,
});

const submit = (): void => {
    form.post(storePage().url);
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Create page" />

        <PageContainer>
            <PageHeader
                title="Create page"
                description="Create a public page with a stable slug, simple content body, and enough SEO metadata for future projects."
            >
                <template #eyebrow>
                    <div class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-medium tracking-[0.2em] text-amber-900 uppercase dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100">
                        <Globe class="size-3.5" />
                        New public page
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

            <form class="grid gap-6" @submit.prevent="submit">
                <section class="rounded-[1.5rem] border border-border/70 bg-card/85 p-5 shadow-sm backdrop-blur">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="title">Title</Label>
                            <Input id="title" v-model="form.title" placeholder="About the platform" />
                            <InputError :message="form.errors.title" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="slug">Slug</Label>
                            <Input id="slug" v-model="form.slug" placeholder="about" />
                            <p class="text-xs text-muted-foreground">Leave blank to generate the slug from the title.</p>
                            <InputError :message="form.errors.slug" />
                        </div>

                        <div class="grid gap-2 md:col-span-2">
                            <Label for="excerpt">Excerpt</Label>
                            <textarea
                                id="excerpt"
                                v-model="form.excerpt"
                                rows="3"
                                class="flex min-h-24 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none ring-offset-background placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]"
                                placeholder="Short summary for listings, cards, and SEO fallback."
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
                                placeholder="Write the public page content here."
                            />
                            <InputError :message="form.errors.content" />
                        </div>
                    </div>
                </section>

                <section class="rounded-[1.5rem] border border-border/70 bg-card/85 p-5 shadow-sm backdrop-blur">
                    <div class="space-y-2">
                        <h2 class="text-lg font-semibold text-foreground">Publishing and SEO</h2>
                        <p class="text-sm leading-6 text-muted-foreground">
                            Draft pages stay private. Publish only when the slug and content are ready for guests.
                        </p>
                    </div>

                    <div class="mt-5 grid gap-5 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="seo_title">SEO title</Label>
                            <Input id="seo_title" v-model="form.seo_title" placeholder="About | Product name" />
                            <InputError :message="form.errors.seo_title" />
                        </div>

                        <div class="grid gap-2 md:col-span-2">
                            <Label for="seo_description">SEO description</Label>
                            <textarea
                                id="seo_description"
                                v-model="form.seo_description"
                                rows="3"
                                class="flex min-h-24 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none ring-offset-background placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]"
                                placeholder="Describe the page for search previews."
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
                            <div class="text-sm font-medium text-foreground">Publish immediately</div>
                            <p class="mt-1 text-sm leading-6 text-muted-foreground">
                                When enabled, the page becomes available on its public slug as soon as you save it.
                            </p>
                        </div>
                    </label>
                    <InputError :message="form.errors.is_published" />
                </section>

                <div class="flex items-center justify-end gap-3">
                    <Button type="submit" :disabled="form.processing">
                        <FileText class="size-4" />
                        Create page
                    </Button>
                </div>
            </form>
        </PageContainer>
    </AppLayout>
</template>
