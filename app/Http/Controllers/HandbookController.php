<?php

namespace App\Http\Controllers;

use App\Http\Requests\HandbookIndexRequest;
use App\Support\HandbookLibrary;
use Inertia\Inertia;
use Inertia\Response;

class HandbookController extends Controller
{
    public function __invoke(HandbookIndexRequest $request, HandbookLibrary $handbookLibrary): Response
    {
        $payload = $handbookLibrary->buildPayload(
            documentKey: $request->safe()->string('document')->trim()->toString(),
            lessonKey: $request->safe()->string('lesson')->trim()->toString(),
        );

        return Inertia::render('handbook/Index', $payload);
    }
}
