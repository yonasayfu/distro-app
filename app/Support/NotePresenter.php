<?php

namespace App\Support;

use App\Models\Note;
use Illuminate\Database\Eloquent\Collection;

class NotePresenter
{
    /**
     * @param  Collection<int, Note>  $notes
     * @return array<int, array{id: int, content: string, author: string|null, createdAt: string|null, canDelete: bool}>
     */
    public static function collection(Collection $notes, bool $canDelete): array
    {
        return $notes->map(fn (Note $note): array => [
            'id' => $note->id,
            'content' => $note->content,
            'author' => $note->author?->name,
            'createdAt' => $note->created_at?->toDateTimeString(),
            'canDelete' => $canDelete,
        ])->values()->all();
    }
}
