<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { BookOpenText, FileText, GraduationCap, Layers3 } from 'lucide-vue-next';
import PageContainer from '@/components/PageContainer.vue';
import PageHeader from '@/components/PageHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as handbookIndex } from '@/routes/handbook';
import type {
    BreadcrumbItem,
    HandbookDocument,
    HandbookFilters,
    HandbookGroup,
    HandbookLesson,
} from '@/types';

type Props = {
    groups: HandbookGroup[];
    filters: HandbookFilters;
    currentDocument: HandbookDocument;
    currentLesson: HandbookLesson | null;
    lessonItems: HandbookLesson[];
};

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Handbook',
        href: handbookIndex(),
    },
];

const selectDocument = (documentKey: string): void => {
    router.get(
        handbookIndex.url({
            query: {
                document: documentKey,
            },
        }),
        {},
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
            only: ['groups', 'filters', 'currentDocument', 'currentLesson', 'lessonItems'],
        },
    );
};

const selectLesson = (lessonKey: string): void => {
    router.get(
        handbookIndex.url({
            query: {
                document: props.currentDocument.key,
                lesson: lessonKey,
            },
        }),
        {},
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
            only: ['groups', 'filters', 'currentDocument', 'currentLesson', 'lessonItems'],
        },
    );
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Handbook" />

        <PageContainer>
            <PageHeader
                title="Handbook"
                description="Read the roadmap, workflow guides, and Laravel learning archive inside the app while you build."
            >
                <template #eyebrow>
                    <div class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-xs font-medium tracking-[0.2em] text-sky-900 uppercase dark:border-sky-500/30 dark:bg-sky-500/10 dark:text-sky-100">
                        <BookOpenText class="size-3.5" />
                        Internal knowledge
                    </div>
                </template>
            </PageHeader>

            <div class="grid gap-6 xl:grid-cols-[320px_1fr]">
                <aside class="space-y-4">
                    <section class="rounded-[1.6rem] border border-border/70 bg-card/90 p-4 shadow-sm">
                        <div class="flex items-center gap-2 text-sm font-semibold text-foreground">
                            <Layers3 class="size-4" />
                            Document groups
                        </div>
                        <div class="mt-4 space-y-4">
                            <div
                                v-for="group in groups"
                                :key="group.key"
                                class="rounded-[1.2rem] border border-border/60 bg-background/75 p-3"
                            >
                                <div class="text-sm font-semibold text-foreground">{{ group.title }}</div>
                                <p class="mt-1 text-xs leading-6 text-muted-foreground">{{ group.description }}</p>
                                <div class="mt-3 space-y-2">
                                    <button
                                        v-for="document in group.documents"
                                        :key="document.key"
                                        type="button"
                                        class="flex w-full items-start justify-between gap-3 rounded-2xl px-3 py-3 text-left transition"
                                        :class="document.key === filters.document
                                            ? 'bg-stone-900 text-stone-50 shadow-sm'
                                            : 'bg-background hover:bg-muted/60'"
                                        @click="selectDocument(document.key)"
                                    >
                                        <div class="min-w-0">
                                            <div class="text-sm font-medium">{{ document.title }}</div>
                                            <div
                                                class="mt-1 text-xs leading-5"
                                                :class="document.key === filters.document ? 'text-stone-300' : 'text-muted-foreground'"
                                            >
                                                {{ document.description }}
                                            </div>
                                        </div>
                                        <Badge
                                            variant="outline"
                                            class="shrink-0"
                                            :class="document.key === filters.document ? 'border-stone-700 text-stone-200' : ''"
                                        >
                                            {{ document.mode === 'lessons' ? 'Lessons' : 'Doc' }}
                                        </Badge>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section
                        v-if="currentDocument.mode === 'lessons'"
                        class="rounded-[1.6rem] border border-border/70 bg-card/90 p-4 shadow-sm"
                    >
                        <div class="flex items-center gap-2 text-sm font-semibold text-foreground">
                            <GraduationCap class="size-4" />
                            Lesson archive
                        </div>
                        <p class="mt-2 text-xs leading-6 text-muted-foreground">
                            Open the implementation notes entry by entry while you work.
                        </p>
                        <div class="mt-4 max-h-[32rem] space-y-2 overflow-y-auto pr-1">
                            <Button
                                variant="ghost"
                                class="w-full justify-start rounded-2xl"
                                :class="filters.lesson === '' ? 'bg-muted' : ''"
                                @click="selectDocument(currentDocument.key)"
                            >
                                <FileText class="size-4" />
                                Overview
                            </Button>
                            <button
                                v-for="lesson in lessonItems"
                                :key="lesson.key"
                                type="button"
                                class="w-full rounded-2xl px-3 py-3 text-left text-sm transition"
                                :class="lesson.key === filters.lesson
                                    ? 'bg-stone-900 text-stone-50 shadow-sm'
                                    : 'bg-background hover:bg-muted/60'"
                                @click="selectLesson(lesson.key)"
                            >
                                <div class="font-medium">
                                    <span v-if="lesson.entryNumber !== null" class="mr-2 text-xs uppercase tracking-[0.16em] opacity-70">
                                        Lesson {{ lesson.entryNumber }}
                                    </span>
                                    {{ lesson.title }}
                                </div>
                            </button>
                        </div>
                    </section>
                </aside>

                <section class="space-y-4">
                    <div class="rounded-[1.6rem] border border-border/70 bg-card/90 p-6 shadow-sm">
                        <div class="flex flex-wrap items-center gap-3">
                            <Badge variant="outline">{{ currentDocument.group }}</Badge>
                            <Badge v-if="currentLesson?.entryNumber !== null" variant="secondary">
                                Lesson {{ currentLesson?.entryNumber }}
                            </Badge>
                        </div>
                        <h2 class="mt-4 text-3xl font-semibold tracking-[-0.03em] text-foreground">
                            {{ currentLesson?.title ?? currentDocument.title }}
                        </h2>
                        <p class="mt-3 max-w-3xl text-sm leading-7 text-muted-foreground">
                            {{ currentLesson ? 'Implementation archive entry with code-level reasoning and verification notes.' : currentDocument.description }}
                        </p>
                    </div>

                    <article
                        class="rounded-[1.8rem] border border-border/70 bg-card/90 p-6 shadow-sm backdrop-blur"
                    >
                        <div
                            class="handbook-prose max-w-none"
                            v-html="currentDocument.html"
                        />
                    </article>
                </section>
            </div>
        </PageContainer>
    </AppLayout>
</template>

<style scoped>
.handbook-prose :deep(h1) {
    margin-top: 0;
    font-size: 1.9rem;
    line-height: 1.1;
    font-weight: 700;
    letter-spacing: -0.03em;
}

.handbook-prose :deep(h2) {
    margin-top: 2.25rem;
    font-size: 1.45rem;
    line-height: 1.2;
    font-weight: 700;
    letter-spacing: -0.02em;
}

.handbook-prose :deep(h3) {
    margin-top: 1.75rem;
    font-size: 1.05rem;
    font-weight: 700;
}

.handbook-prose :deep(p),
.handbook-prose :deep(li) {
    color: hsl(var(--muted-foreground));
    line-height: 1.85;
}

.handbook-prose :deep(ul),
.handbook-prose :deep(ol) {
    margin-top: 1rem;
    padding-left: 1.35rem;
}

.handbook-prose :deep(code) {
    border-radius: 0.55rem;
    background: color-mix(in srgb, hsl(var(--muted)) 84%, white 16%);
    padding: 0.14rem 0.42rem;
    font-size: 0.9em;
}

.handbook-prose :deep(pre) {
    overflow-x: auto;
    border-radius: 1.1rem;
    background: #171717;
    padding: 1rem;
    color: #f5f5f4;
}

.handbook-prose :deep(pre code) {
    background: transparent;
    padding: 0;
    color: inherit;
}

.handbook-prose :deep(blockquote) {
    margin-top: 1.25rem;
    border-left: 3px solid hsl(var(--border));
    padding-left: 1rem;
    color: hsl(var(--muted-foreground));
}

.handbook-prose :deep(a) {
    color: hsl(var(--primary));
    text-decoration: underline;
    text-underline-offset: 0.2rem;
}
</style>
