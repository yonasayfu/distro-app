<?php

namespace App\Support;

use App\Models\ImportRun;
use App\Models\Page;
use App\PageStatus;

class PageCsvImporter
{
    /**
     * @return array{imported: int, skipped: int}
     */
    public static function run(ImportRun $importRun): array
    {
        $rows = collect($importRun->preview_rows ?? []);
        $imported = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            if (($row['valid'] ?? false) !== true) {
                $skipped++;

                continue;
            }

            if (Page::withTrashed()->where('slug', $row['slug'])->exists()) {
                $skipped++;

                continue;
            }

            $status = PageStatus::from($row['status']);

            Page::query()->create([
                'title' => $row['title'],
                'slug' => $row['slug'],
                'excerpt' => $row['excerpt'],
                'content' => $row['content'],
                'seo_title' => $row['seo_title'],
                'seo_description' => $row['seo_description'],
                'status' => $status,
                'is_published' => $status === PageStatus::Published,
                'published_at' => $status === PageStatus::Published ? now() : null,
            ]);

            $imported++;
        }

        return [
            'imported' => $imported,
            'skipped' => $skipped,
        ];
    }
}
