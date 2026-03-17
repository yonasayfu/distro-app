<?php

namespace App\Support;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiPagination
{
    /**
     * Build a consistent paginated API response.
     *
     * @param  array<string, mixed>  $meta
     */
    public static function response(
        Request $request,
        LengthAwarePaginator $paginator,
        JsonResource|Responsable $resourceCollection,
        array $meta = [],
    ): JsonResponse {
        return response()->json([
            'data' => $resourceCollection->resolve($request),
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
            'meta' => array_merge($meta, [
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'from' => $paginator->firstItem(),
                    'to' => $paginator->lastItem(),
                ],
            ]),
        ]);
    }
}
