<?php

namespace App\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class HandbookLibrary
{
    /**
     * @var array<int, array{key: string, title: string, description: string, documents: array<int, array{key: string, title: string, description: string, path: string, mode: string}>}>
     */
    private const GROUPS = [
        [
            'key' => 'foundation',
            'title' => 'Foundation',
            'description' => 'Scope, roadmap, and platform direction.',
            'documents' => [
                [
                    'key' => 'roadmap',
                    'title' => 'Boilerplate Roadmap',
                    'description' => 'High-level phase plan for the starter.',
                    'path' => 'TheRoadmap/BoilerplateRoadmap.md',
                    'mode' => 'document',
                ],
                [
                    'key' => 'task-list',
                    'title' => 'Task List',
                    'description' => 'Execution checklist by phase.',
                    'path' => 'TheRoadmap/BoilerplateTaskList.md',
                    'mode' => 'document',
                ],
                [
                    'key' => 'public-website-phase',
                    'title' => 'Public Website Phase',
                    'description' => 'Guest-facing website plan and content structure.',
                    'path' => 'TheRoadmap/publicWebsitePhase.md',
                    'mode' => 'document',
                ],
            ],
        ],
        [
            'key' => 'workflow',
            'title' => 'Workflow',
            'description' => 'How this boilerplate is operated, deployed, and extended.',
            'documents' => [
                [
                    'key' => 'git-guidance',
                    'title' => 'Git Guidance',
                    'description' => 'Branching and commit workflow for this project.',
                    'path' => 'TheRoadmap/gitguidance.md',
                    'mode' => 'document',
                ],
                [
                    'key' => 'mcp-guidance',
                    'title' => 'MCP Guidance',
                    'description' => 'How MCP servers help development work in this repo.',
                    'path' => 'TheRoadmap/mcpguidance.md',
                    'mode' => 'document',
                ],
                [
                    'key' => 'integration-guidance',
                    'title' => 'Integration Guidance',
                    'description' => 'How to add new modules, entities, and pages.',
                    'path' => 'TheRoadmap/guidanceIntergartion.md',
                    'mode' => 'document',
                ],
                [
                    'key' => 'production-readiness',
                    'title' => 'Production Readiness',
                    'description' => 'Deployment guidance for Cloud, Forge, and VPS targets.',
                    'path' => 'TheRoadmap/production-readiness.md',
                    'mode' => 'document',
                ],
                [
                    'key' => 'mail-testing',
                    'title' => 'Mail Testing',
                    'description' => 'Mailpit and local email testing workflow.',
                    'path' => 'TheRoadmap/mailtesting.md',
                    'mode' => 'document',
                ],
                [
                    'key' => 'ai-handoff',
                    'title' => 'AI Handoff',
                    'description' => 'Prompting and document order for other coding agents.',
                    'path' => 'TheRoadmap/talkewithai.md',
                    'mode' => 'document',
                ],
            ],
        ],
        [
            'key' => 'learning',
            'title' => 'Laravel Lessons',
            'description' => 'The running learning archive for this boilerplate.',
            'documents' => [
                [
                    'key' => 'laravelbasics',
                    'title' => 'Laravel Basics',
                    'description' => 'Implementation archive with code-level lesson entries.',
                    'path' => 'TheRoadmap/laravelbasics.md',
                    'mode' => 'lessons',
                ],
            ],
        ],
    ];

    /**
     * @return array{
     *     groups: array<int, array{key: string, title: string, description: string, documents: array<int, array{key: string, title: string, description: string, mode: string}>}>,
     *     filters: array{document: string, lesson: string},
     *     currentDocument: array{key: string, title: string, description: string, html: string, mode: string, group: string},
     *     currentLesson: array{key: string, title: string, html: string, entryNumber: int|null}|null,
     *     lessonItems: array<int, array{key: string, title: string, entryNumber: int|null}>
     * }
     */
    public function buildPayload(string $documentKey = '', string $lessonKey = ''): array
    {
        $documents = collect(self::GROUPS)
            ->flatMap(fn (array $group): array => array_map(
                fn (array $document): array => [...$document, 'group' => $group['title']],
                $group['documents'],
            ))
            ->keyBy('key');

        $selectedDocument = $documents->get($documentKey) ?? $documents->first();

        if ($selectedDocument === null) {
            throw new RuntimeException('No handbook documents are configured.');
        }

        $markdown = $this->readMarkdown($selectedDocument['path']);
        $currentLesson = null;
        $lessonItems = [];
        $html = $this->renderMarkdown($markdown);

        if ($selectedDocument['mode'] === 'lessons') {
            [$overviewMarkdown, $lessons] = $this->parseLessons($markdown);

            $lessonItems = array_map(
                fn (array $lesson): array => [
                    'key' => $lesson['key'],
                    'title' => $lesson['title'],
                    'entryNumber' => $lesson['entryNumber'],
                ],
                $lessons,
            );

            $selectedLesson = collect($lessons)->firstWhere('key', $lessonKey) ?? null;

            if ($selectedLesson !== null) {
                $currentLesson = [
                    'key' => $selectedLesson['key'],
                    'title' => $selectedLesson['title'],
                    'html' => $this->renderMarkdown($selectedLesson['markdown']),
                    'entryNumber' => $selectedLesson['entryNumber'],
                ];

                $html = $currentLesson['html'];
            } else {
                $html = $this->renderMarkdown($overviewMarkdown);
            }
        }

        return [
            'groups' => array_map(
                fn (array $group): array => [
                    'key' => $group['key'],
                    'title' => $group['title'],
                    'description' => $group['description'],
                    'documents' => array_map(
                        fn (array $document): array => [
                            'key' => $document['key'],
                            'title' => $document['title'],
                            'description' => $document['description'],
                            'mode' => $document['mode'],
                        ],
                        $group['documents'],
                    ),
                ],
                self::GROUPS,
            ),
            'filters' => [
                'document' => $selectedDocument['key'],
                'lesson' => $currentLesson['key'] ?? '',
            ],
            'currentDocument' => [
                'key' => $selectedDocument['key'],
                'title' => $selectedDocument['title'],
                'description' => $selectedDocument['description'],
                'html' => $html,
                'mode' => $selectedDocument['mode'],
                'group' => $selectedDocument['group'],
            ],
            'currentLesson' => $currentLesson,
            'lessonItems' => $lessonItems,
        ];
    }

    private function readMarkdown(string $path): string
    {
        $absolutePath = base_path($path);

        if (! File::exists($absolutePath)) {
            throw new RuntimeException("Handbook document not found: {$path}");
        }

        return File::get($absolutePath);
    }

    private function renderMarkdown(string $markdown): string
    {
        return Str::markdown($markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    /**
     * @return array{0: string, 1: array<int, array{key: string, title: string, markdown: string, entryNumber: int|null}>}
     */
    private function parseLessons(string $markdown): array
    {
        $parts = preg_split('/(?=^## Entry \d+:)/m', $markdown) ?: [];
        $overview = array_shift($parts) ?? $markdown;

        $lessons = array_values(array_filter(array_map(function (string $part): ?array {
            if (! preg_match('/^## Entry (?P<number>\d+): (?P<title>.+)$/m', $part, $matches)) {
                return null;
            }

            $title = trim($matches['title']);
            $entryNumber = (int) $matches['number'];

            return [
                'key' => 'entry-'.Str::slug("{$entryNumber}-{$title}"),
                'title' => $title,
                'markdown' => trim($part),
                'entryNumber' => $entryNumber,
            ];
        }, $parts)));

        return [$overview, $lessons];
    }
}
