<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PreviewPageImportRequest;
use App\Http\Requests\Admin\RunPageImportRequest;
use App\Models\ImportRun;
use App\Models\Page;
use App\Support\ActivityLogger;
use App\Support\PageCsvImporter;
use App\Support\PageImportPreviewer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PageImportController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('create', Page::class);

        return Inertia::render('admin/Pages/Import', [
            'preview' => null,
            'importRuns' => $this->recentRuns(),
        ]);
    }

    public function preview(PreviewPageImportRequest $request): Response
    {
        $this->authorize('create', Page::class);

        $preview = PageImportPreviewer::preview($request->file('file'));

        $importRun = ImportRun::query()->create([
            'user_id' => $request->user()?->id,
            'resource' => 'pages',
            'status' => 'previewed',
            'file_name' => $request->file('file')->getClientOriginalName(),
            'rows_count' => $preview['summary']['rows'],
            'valid_rows_count' => $preview['summary']['validRows'],
            'summary' => $preview['summary'],
            'preview_rows' => $preview['rows'],
        ]);

        return Inertia::render('admin/Pages/Import', [
            'preview' => [
                'importRunId' => $importRun->id,
                'fileName' => $importRun->file_name,
                'rows' => $preview['rows'],
                'summary' => $preview['summary'],
            ],
            'importRuns' => $this->recentRuns(),
        ]);
    }

    public function store(RunPageImportRequest $request): RedirectResponse
    {
        $this->authorize('create', Page::class);

        $importRun = ImportRun::query()->findOrFail($request->validated('import_run_id'));

        $result = PageCsvImporter::run($importRun);

        $importRun->update([
            'status' => 'completed',
            'imported_rows_count' => $result['imported'],
            'summary' => [
                ...($importRun->summary ?? []),
                'imported' => $result['imported'],
                'skipped' => $result['skipped'],
            ],
            'completed_at' => now(),
        ]);

        ActivityLogger::record(
            actor: $request->user(),
            event: 'pages.imported',
            description: "Imported {$result['imported']} pages from {$importRun->file_name}.",
            subject: $importRun,
            properties: [
                'file' => $importRun->file_name,
                'imported' => $result['imported'],
                'skipped' => $result['skipped'],
            ],
            request: $request,
        );

        return to_route('pages.index')->with('success', 'Pages imported successfully.');
    }

    /**
     * @return array<int, array{id: int, fileName: string, status: string, rowsCount: int, validRowsCount: int, importedRowsCount: int, completedAt: string|null, createdAt: string|null}>
     */
    private function recentRuns(): array
    {
        return ImportRun::query()
            ->where('resource', 'pages')
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn (ImportRun $run): array => [
                'id' => $run->id,
                'fileName' => $run->file_name,
                'status' => $run->status,
                'rowsCount' => $run->rows_count,
                'validRowsCount' => $run->valid_rows_count,
                'importedRowsCount' => $run->imported_rows_count,
                'completedAt' => $run->completed_at?->toDateTimeString(),
                'createdAt' => $run->created_at?->toDateTimeString(),
            ])
            ->all();
    }
}
