<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Search, SearchX } from 'lucide-vue-next';
import { ref } from 'vue';
import EmptyState from '@/components/EmptyState.vue';
import PageContainer from '@/components/PageContainer.vue';
import PageHeader from '@/components/PageHeader.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as searchIndex } from '@/routes/search';
import type { BreadcrumbItem, SearchFilters, SearchResultGroup } from '@/types';

type Props = {
    filters: SearchFilters;
    results: SearchResultGroup[];
};

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Search',
        href: searchIndex(),
    },
];

const query = ref(props.filters.q);

const submit = (): void => {
    router.get(searchIndex.url({ query: { q: query.value || undefined } }), {}, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Global search" />

        <PageContainer>
            <PageHeader
                title="Global search"
                description="Search across the modules your current role is allowed to access."
            />

            <section class="rounded-[1.5rem] border border-border/70 bg-card/85 p-5 shadow-sm backdrop-blur">
                <form class="flex flex-col gap-3 md:flex-row" @submit.prevent="submit">
                    <Input
                        v-model="query"
                        placeholder="Search users, roles, notifications, or activity logs"
                        class="h-11"
                    />
                    <Button type="submit" class="h-11">
                        <Search class="size-4" />
                        Search
                    </Button>
                </form>
            </section>

            <div v-if="filters.q === ''">
                <EmptyState
                    title="Start with a query"
                    description="Search is permission-aware, so results only come from modules you can already access."
                    :icon="Search"
                />
            </div>

            <div v-else-if="results.length === 0">
                <EmptyState
                    title="No matches found"
                    description="Try a shorter keyword or search for a user, role, notification title, or event key."
                    :icon="SearchX"
                />
            </div>

            <section v-else class="space-y-6">
                <div
                    v-for="group in results"
                    :key="group.key"
                    class="rounded-[1.5rem] border border-border/70 bg-card/85 p-5 shadow-sm backdrop-blur"
                >
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold">{{ group.title }}</h2>
                            <p class="text-sm text-muted-foreground">{{ group.count }} result(s)</p>
                        </div>
                    </div>

                    <div class="mt-4 divide-y divide-border/60">
                        <Link
                            v-for="item in group.items"
                            :key="item.id"
                            :href="item.href"
                            class="flex items-start justify-between gap-4 py-4 transition hover:opacity-80"
                        >
                            <div>
                                <div class="font-medium">{{ item.title }}</div>
                                <div class="mt-1 text-sm text-muted-foreground">{{ item.description }}</div>
                            </div>
                            <div v-if="item.meta" class="shrink-0 text-xs text-muted-foreground">
                                {{ item.meta }}
                            </div>
                        </Link>
                    </div>
                </div>
            </section>
        </PageContainer>
    </AppLayout>
</template>
