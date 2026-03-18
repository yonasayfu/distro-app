<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePageRequest;
use App\Http\Requests\Admin\UpdatePageRequest;
use App\Models\Page;
use App\PageStatus;
use App\Support\ActivityLogger;
use App\Support\NotePresenter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PageManagementController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Page::class);

        $search = $request->string('search')->trim()->toString();

        return Inertia::render('admin/Pages/Index', [
            'pages' => Page::query()
                ->when($search !== '', function ($query) use ($search): void {
                    $query->where(function ($pageQuery) use ($search): void {
                        $pageQuery
                            ->where('title', 'ilike', "%{$search}%")
                            ->orWhere('slug', 'ilike', "%{$search}%")
                            ->orWhere('excerpt', 'ilike', "%{$search}%");
                    });
                })
                ->orderByDesc('updated_at')
                ->paginate(10)
                ->withQueryString()
                ->through(fn (Page $page): array => $this->pageSummary($page)),
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Page::class);

        return Inertia::render('admin/Pages/Create');
    }

    public function store(StorePageRequest $request): RedirectResponse
    {
        $this->authorize('create', Page::class);

        $page = Page::query()->create($this->payload($request->validated()));

        ActivityLogger::record(
            actor: $request->user(),
            event: 'pages.created',
            description: "Created page {$page->slug}.",
            subject: $page,
            properties: [
                'status' => $page->status->value,
            ],
            request: $request,
        );

        return to_route('pages.edit', $page)->with('success', 'Page created successfully.');
    }

    public function edit(Page $page): Response
    {
        $this->authorize('view', $page);

        $page->load('notes.author:id,name');

        return Inertia::render('admin/Pages/Edit', [
            'page' => [
                ...$this->pageSummary($page),
                'content' => $page->content,
                'seoTitle' => $page->seo_title,
                'seoDescription' => $page->seo_description,
                'notes' => NotePresenter::collection(
                    $page->notes,
                    request()->user()?->can('notes.delete') ?? false,
                ),
            ],
            'noteTarget' => [
                'type' => 'page',
                'id' => $page->id,
                'title' => $page->title,
            ],
            'canCreateNotes' => request()->user()?->can('notes.create') ?? false,
        ]);
    }

    public function update(UpdatePageRequest $request, Page $page): RedirectResponse
    {
        $this->authorize('update', $page);

        $page->update($this->payload($request->validated(), $page));

        ActivityLogger::record(
            actor: $request->user(),
            event: 'pages.updated',
            description: "Updated page {$page->slug}.",
            subject: $page,
            properties: [
                'status' => $page->status->value,
            ],
            request: $request,
        );

        return to_route('pages.edit', $page)->with('success', 'Page updated successfully.');
    }

    public function destroy(Request $request, Page $page): RedirectResponse
    {
        $this->authorize('delete', $page);

        ActivityLogger::record(
            actor: $request->user(),
            event: 'pages.deleted',
            description: "Deleted page {$page->slug}.",
            subject: $page,
            request: $request,
        );

        $page->delete();

        return to_route('pages.index')->with('success', 'Page deleted successfully.');
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function payload(array $validated, ?Page $page = null): array
    {
        $status = PageStatus::from($validated['status']);
        $publishedAt = $status === PageStatus::Published ? ($page?->published_at ?? now()) : null;

        return [
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'excerpt' => $validated['excerpt'] ?: null,
            'content' => $validated['content'],
            'seo_title' => $validated['seo_title'] ?: null,
            'seo_description' => $validated['seo_description'] ?: null,
            'status' => $status,
            'is_published' => $status === PageStatus::Published,
            'published_at' => $publishedAt,
        ];
    }

    /**
     * @return array{id: int, title: string, slug: string, excerpt: string|null, status: string, statusLabel: string, statusTone: string, isPublished: bool, publishedAt: string|null, updatedAt: string|null, publicUrl: string|null}
     */
    private function pageSummary(Page $page): array
    {
        return [
            'id' => $page->id,
            'title' => $page->title,
            'slug' => $page->slug,
            'excerpt' => $page->excerpt,
            'status' => $page->status->value,
            'statusLabel' => $page->status->label(),
            'statusTone' => $page->status->tone(),
            'isPublished' => $page->is_published,
            'publishedAt' => $page->published_at?->toDateTimeString(),
            'updatedAt' => $page->updated_at?->toDateTimeString(),
            'publicUrl' => $page->is_published ? route('public-pages.show', ['page' => $page->slug]) : null,
        ];
    }
}
