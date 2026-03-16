<script setup lang="ts">
import AlertError from '@/components/AlertError.vue';

type Props = {
    title?: string;
    errors: string[];
};

defineProps<Props>();
</script>

<template>
    <section
        class="rounded-[1.5rem] border border-border/70 bg-card/80 px-6 py-6 shadow-sm"
    >
        <AlertError :errors="errors" :title="title" />
    </section>
</template>
