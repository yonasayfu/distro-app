<?php

namespace App\Support;

use App\Models\Media;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class MediaUploader
{
    public static function store(UploadedFile $file, User $user, string $collection = 'library', string $disk = 'local'): Media
    {
        $normalizedCollection = str($collection)->trim()->lower()->slug()->toString() ?: 'library';
        $directory = 'media/'.$normalizedCollection;
        $storedPath = $file->store($directory, $disk);

        return Media::query()->create([
            'uploaded_by' => $user->id,
            'collection' => $normalizedCollection,
            'disk' => $disk,
            'directory' => $directory,
            'path' => $storedPath,
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => basename($storedPath),
            'extension' => $file->getClientOriginalExtension() ?: null,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'metadata' => [
                'generated_name' => $file->hashName(),
            ],
        ]);
    }
}
