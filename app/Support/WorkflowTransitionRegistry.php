<?php

namespace App\Support;

use App\PageStatus;

class WorkflowTransitionRegistry
{
    /**
     * @return array<string, list<string>>
     */
    public static function pageTransitions(): array
    {
        return [
            PageStatus::Draft->value => [
                PageStatus::Review->value,
                PageStatus::Published->value,
                PageStatus::Archived->value,
            ],
            PageStatus::Review->value => [
                PageStatus::Draft->value,
                PageStatus::Published->value,
                PageStatus::Archived->value,
            ],
            PageStatus::Published->value => [
                PageStatus::Draft->value,
                PageStatus::Archived->value,
            ],
            PageStatus::Archived->value => [
                PageStatus::Draft->value,
            ],
        ];
    }

    public static function canTransitionPage(string $from, string $to): bool
    {
        return in_array($to, self::pageTransitions()[$from] ?? [], true);
    }
}
