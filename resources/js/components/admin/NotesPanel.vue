<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { MessageSquareText, Trash2 } from 'lucide-vue-next';
import ConfirmActionDialog from '@/components/admin/ConfirmActionDialog.vue';
import EmptyState from '@/components/EmptyState.vue';
import InputError from '@/components/InputError.vue';
import FormSection from '@/components/admin/FormSection.vue';
import { Button } from '@/components/ui/button';
import { destroy as destroyNote, store as storeNote } from '@/routes/notes/index';
import type { ManagedNote, NoteTarget } from '@/types';

type Props = {
    title?: string;
    description?: string;
    target: NoteTarget;
    notes: ManagedNote[];
    canCreate: boolean;
};

const props = withDefaults(defineProps<Props>(), {
    title: 'Internal notes',
    description: 'Keep internal context, handoff details, or operational reminders on the record itself.',
});

const form = useForm({
    noteable_type: props.target.type,
    noteable_id: props.target.id,
    content: '',
});

const submit = (): void => {
    form.post(storeNote().url, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('content');
        },
    });
};

const deleteNote = (note: ManagedNote): void => {
    router.delete(destroyNote(note.id).url, {
        preserveScroll: true,
    });
};
</script>

<template>
    <FormSection :title="title" :description="description">
        <form v-if="canCreate" class="grid gap-3" @submit.prevent="submit">
            <textarea
                v-model="form.content"
                rows="4"
                class="flex min-h-28 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none ring-offset-background placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]"
                :placeholder="`Add an internal note for ${target.title}`"
            />
            <InputError :message="form.errors.content" />

            <div class="flex items-center justify-end gap-3">
                <Button type="submit" :disabled="form.processing || form.content.trim().length < 3">
                    <MessageSquareText class="size-4" />
                    Add note
                </Button>
            </div>
        </form>

        <div class="mt-5 space-y-3">
            <article
                v-for="note in notes"
                :key="note.id"
                class="rounded-2xl border border-border/70 bg-background/80 px-4 py-4"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="text-sm font-medium text-foreground">
                            {{ note.author ?? 'Unknown author' }}
                        </div>
                        <p class="mt-1 text-xs text-muted-foreground">
                            {{ note.createdAt ? new Date(note.createdAt).toLocaleString() : 'Unknown date' }}
                        </p>
                    </div>

                    <ConfirmActionDialog
                        v-if="note.canDelete"
                        title="Delete note"
                        description="Delete this internal note? This action cannot be undone."
                        confirm-label="Delete note"
                        @confirm="deleteNote(note)"
                    >
                        <template #trigger>
                            <Button variant="ghost" size="icon-sm" class="rounded-full text-destructive">
                                <Trash2 class="size-4" />
                            </Button>
                        </template>
                    </ConfirmActionDialog>
                </div>

                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-foreground">
                    {{ note.content }}
                </p>
            </article>

            <EmptyState
                v-if="notes.length === 0"
                title="No notes yet"
                description="Add the first internal note so the next operator sees the context here instead of in chat or email."
                :icon="MessageSquareText"
            />
        </div>
    </FormSection>
</template>
