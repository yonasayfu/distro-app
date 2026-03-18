<?php

namespace App\Support;

use App\PageStatus;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageImportPreviewer
{
    /**
     * @return array{rows: list<array<string, mixed>>, summary: array{rows: int, validRows: int, invalidRows: int}}
     */
    public static function preview(UploadedFile $file): array
    {
        $lines = file($file->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

        if ($lines === []) {
            return [
                'rows' => [],
                'summary' => [
                    'rows' => 0,
                    'validRows' => 0,
                    'invalidRows' => 0,
                ],
            ];
        }

        $headers = collect(str_getcsv(array_shift($lines)))->map(fn (?string $header): string => Str::snake(trim((string) $header)))->all();

        $rows = collect($lines)->values()->map(function (string $line, int $index) use ($headers): array {
            $values = str_getcsv($line);
            $payload = collect($headers)->mapWithKeys(fn (string $header, int $offset): array => [
                $header => trim((string) ($values[$offset] ?? '')),
            ])->all();

            $normalized = [
                'title' => $payload['title'] ?? '',
                'slug' => Str::slug($payload['slug'] !== '' ? $payload['slug'] : ($payload['title'] ?? '')),
                'excerpt' => $payload['excerpt'] !== '' ? $payload['excerpt'] : null,
                'content' => $payload['content'] ?? '',
                'seo_title' => $payload['seo_title'] !== '' ? $payload['seo_title'] : null,
                'seo_description' => $payload['seo_description'] !== '' ? $payload['seo_description'] : null,
                'status' => $payload['status'] !== '' ? $payload['status'] : PageStatus::Draft->value,
            ];

            $validator = Validator::make($normalized, [
                'title' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::notIn(self::reservedSlugs())],
                'excerpt' => ['nullable', 'string', 'max:1000'],
                'content' => ['required', 'string'],
                'seo_title' => ['nullable', 'string', 'max:255'],
                'seo_description' => ['nullable', 'string', 'max:1000'],
                'status' => ['required', Rule::enum(PageStatus::class)],
            ]);

            return [
                'line' => $index + 2,
                'title' => $normalized['title'],
                'slug' => $normalized['slug'],
                'excerpt' => $normalized['excerpt'],
                'content' => $normalized['content'],
                'seo_title' => $normalized['seo_title'],
                'seo_description' => $normalized['seo_description'],
                'status' => $normalized['status'],
                'valid' => ! $validator->fails(),
                'errors' => $validator->errors()->all(),
            ];
        })->all();

        $rowsCollection = collect($rows);

        return [
            'rows' => $rows,
            'summary' => [
                'rows' => $rowsCollection->count(),
                'validRows' => $rowsCollection->where('valid', true)->count(),
                'invalidRows' => $rowsCollection->where('valid', false)->count(),
            ],
        ];
    }

    /**
     * @return list<string>
     */
    private static function reservedSlugs(): array
    {
        return [
            'admin',
            'api',
            'dashboard',
            'exports',
            'handbook',
            'login',
            'notifications',
            'password',
            'register',
            'search',
            'settings',
            'up',
        ];
    }
}
