<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Support\ActivityLogger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportsController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->toString();
        $trashed = $request->string('trashed')->toString();

        $pages = Page::query()
            ->when($trashed === 'with', fn ($query) => $query->withTrashed())
            ->when($trashed === 'only', fn ($query) => $query->onlyTrashed())
            ->when($search !== '', fn ($query) => $this->applySearch($query, $search))
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Page $page): array => [
                'id' => $page->id,
                'title' => $page->title,
                'slug' => $page->slug,
                'status' => $page->status->value,
                'statusLabel' => $page->status->label(),
                'statusTone' => $page->status->tone(),
                'isDeleted' => $page->trashed(),
                'deletedAt' => $page->deleted_at?->toDateTimeString(),
                'updatedAt' => $page->updated_at?->toDateTimeString(),
            ]);

        return Inertia::render('reports/Index', [
            'pageReport' => $pages,
            'filters' => [
                'search' => $search,
                'status' => $status,
                'trashed' => $trashed,
            ],
            'statusOptions' => [
                ['value' => 'draft', 'label' => 'Draft'],
                ['value' => 'review', 'label' => 'In review'],
                ['value' => 'published', 'label' => 'Published'],
                ['value' => 'archived', 'label' => 'Archived'],
            ],
        ]);
    }

    public function pagesCsv(Request $request): StreamedResponse
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->toString();
        $trashed = $request->string('trashed')->toString();

        ActivityLogger::record(
            actor: $request->user(),
            event: 'reports.pages-csv',
            description: 'Downloaded the pages report CSV.',
            properties: [
                'search' => $search,
                'status' => $status,
                'trashed' => $trashed,
            ],
            request: $request,
        );

        $query = Page::query()
            ->when($trashed === 'with', fn ($builder) => $builder->withTrashed())
            ->when($trashed === 'only', fn ($builder) => $builder->onlyTrashed())
            ->when($search !== '', fn ($builder) => $this->applySearch($builder, $search))
            ->when($status !== '', fn ($builder) => $builder->where('status', $status))
            ->orderBy('title');

        return response()->streamDownload(function () use ($query): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Title', 'Slug', 'Status', 'Deleted', 'Updated At']);

            $query->get()->each(function (Page $page) use ($handle): void {
                fputcsv($handle, [
                    $page->title,
                    $page->slug,
                    $page->status->value,
                    $page->trashed() ? 'yes' : 'no',
                    $page->updated_at?->toDateTimeString(),
                ]);
            });

            fclose($handle);
        }, sprintf('pages-report-%s.csv', Str::of(now()->toDateString())->replace('-', '')), [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function applySearch(Builder $query, string $search): void
    {
        $term = '%'.Str::lower($search).'%';

        $query->where(function (Builder $pageQuery) use ($term): void {
            $pageQuery
                ->whereRaw('LOWER(title) LIKE ?', [$term])
                ->orWhereRaw('LOWER(slug) LIKE ?', [$term])
                ->orWhereRaw('LOWER(COALESCE(excerpt, ?)) LIKE ?', ['', $term]);
        });
    }
}
