<script setup lang="ts">
type Props = {
    class?: string;
};

defineProps<Props>();
</script>

<template>
    <div
        class="flex min-h-full flex-1 flex-col gap-6 px-4 py-4 md:px-6 md:py-6"
        :class="$props.class"
    >
        <slot />
    </div>
</template>
