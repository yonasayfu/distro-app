<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/public/PublicLayout.vue';

type Props = {
    page: {
        title: string;
        slug: string;
        excerpt: string | null;
        content: string;
        seoTitle: string;
        seoDescription: string | null;
        publishedAt: string | null;
    };
};

defineProps<Props>();
</script>

<template>
    <PublicLayout :title="page.title">
        <Head>
            <title>{{ page.seoTitle }}</title>
            <meta v-if="page.seoDescription" head-key="description" name="description" :content="page.seoDescription" />
        </Head>

        <section class="mx-auto max-w-4xl px-5 py-16 lg:px-8 lg:py-24">
            <article class="rounded-[2rem] border border-stone-900/10 bg-white/80 p-8 shadow-sm backdrop-blur lg:p-10">
                <div class="text-[0.72rem] font-semibold uppercase tracking-[0.22em] text-stone-500">
                    Public page
                </div>

                <h1 class="mt-5 text-4xl font-semibold tracking-[-0.04em] text-stone-950 lg:text-5xl">
                    {{ page.title }}
                </h1>

                <p v-if="page.excerpt" class="mt-5 text-base leading-8 text-stone-700 lg:text-lg">
                    {{ page.excerpt }}
                </p>

                <div class="mt-6 text-sm text-stone-500">
                    <span v-if="page.publishedAt">Published {{ page.publishedAt }}</span>
                    <span v-else>Published page</span>
                </div>

                <div class="mt-10 h-px bg-stone-900/10" />

                <div class="mt-10 whitespace-pre-line text-base leading-8 text-stone-800">
                    {{ page.content }}
                </div>
            </article>
        </section>
    </PublicLayout>
</template>
