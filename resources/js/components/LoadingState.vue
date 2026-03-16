<template>
    <section
        class="rounded-[1.5rem] border border-border/70 bg-card/80 px-6 py-6 shadow-sm"
    >
        <div class="space-y-4">
            <div class="h-4 w-28 animate-pulse rounded-full bg-muted" />
            <div class="h-8 w-1/3 animate-pulse rounded-full bg-muted" />
            <div class="grid gap-3 md:grid-cols-3">
                <div
                    v-for="index in 3"
                    :key="index"
                    class="h-28 animate-pulse rounded-2xl bg-muted"
                />
            </div>
        </div>
    </section>
</template>
