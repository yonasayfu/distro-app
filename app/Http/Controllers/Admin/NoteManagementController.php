<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNoteRequest;
use App\Models\Note;
use App\Support\ActivityLogger;
use App\Support\NoteableRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class NoteManagementController extends Controller
{
    public function store(StoreNoteRequest $request): RedirectResponse
    {
        $this->authorize('create', Note::class);

        $noteableType = $request->validated('noteable_type');
        $noteableId = (int) $request->validated('noteable_id');
        $noteable = NoteableRegistry::resolve($noteableType, $noteableId);

        abort_if($noteable === null, 404);

        Gate::forUser($request->user())->authorize('view', $noteable);

        $note = $noteable->notes()->create([
            'author_id' => $request->user()?->id,
            'content' => $request->validated('content'),
        ]);

        ActivityLogger::record(
            actor: $request->user(),
            event: 'notes.created',
            description: "Added a note to {$noteableType} #{$noteableId}.",
            subject: $note,
            properties: [
                'noteable_type' => $noteableType,
                'noteable_id' => $noteableId,
            ],
            request: $request,
        );

        return back()->with('success', 'Note added successfully.');
    }

    public function destroy(Request $request, Note $note): RedirectResponse
    {
        $this->authorize('delete', $note);

        ActivityLogger::record(
            actor: $request->user(),
            event: 'notes.deleted',
            description: "Deleted note {$note->id}.",
            subject: $note,
            properties: [
                'noteable_type' => $note->noteable_type,
                'noteable_id' => $note->noteable_id,
            ],
            request: $request,
        );

        $note->delete();

        return back()->with('success', 'Note deleted successfully.');
    }
}
