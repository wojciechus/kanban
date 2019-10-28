<?php

namespace App\Services;

use App\Enum\KanbanIssueStatus;
use App\Models\IssueViewModel;
use http\Exception\InvalidArgumentException;

class IssuesSorter
{
    public function sortIssues(array $issues): array
    {
        if (!$this->isProperArray($issues)) {
            throw new InvalidArgumentException();
        }

        if (!empty($issues[KanbanIssueStatus::ACTIVE])) {
            usort($issues[KanbanIssueStatus::ACTIVE], function (IssueViewModel $a, IssueViewModel $b) {
                if ($a->isPaused() === $b->isPaused()) {
                    return strcmp($a->getTitle(), $b->getTitle());
                }

                return $a->isPaused() ? -1 : 1;
            });
        }

        return $issues;
    }

    private function isProperArray(array $issues): bool
    {
        foreach ($issues as $issuesSingleType) {
            foreach ($issuesSingleType as $issue) {
                if (!$issue instanceOf IssueViewModel) {
                    return false;
                }
            }
        }

        return true;
    }
}