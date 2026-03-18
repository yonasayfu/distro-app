<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\PageStatus;
use Inertia\Inertia;
use Inertia\Response;

class PublicPageController extends Controller
{
    public function __invoke(Page $page): Response
    {
        abort_unless($page->status === PageStatus::Published && $page->published_at !== null, 404);

        return Inertia::render('public/Pages/Show', [
            'page' => [
                'title' => $page->title,
                'slug' => $page->slug,
                'excerpt' => $page->excerpt,
                'content' => $page->content,
                'seoTitle' => $page->seo_title ?: $page->title,
                'seoDescription' => $page->seo_description ?: $page->excerpt,
                'publishedAt' => $page->published_at?->toDateString(),
            ],
        ]);
    }
}
