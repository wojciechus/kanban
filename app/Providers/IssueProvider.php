<?php

namespace App\Providers;

use App\Enum\KanbanIssueStatus;
use App\Models\Issue;
use App\Models\IssueViewModel;
use App\Repositories\IssueRepository;
use App\Services\IssuesSorter;

class IssueProvider
{
    private $issueRepository;
    private $issuesSorter;

    public function __construct(IssueRepository $issueRepository, IssuesSorter $issuesSorter)
    {
        $this->issueRepository = $issueRepository;
        $this->issuesSorter = $issuesSorter;
    }

    public function getIssues(string $repository, int $milestoneId): array
    {
        $issues = $this->prepareIssues($repository, $milestoneId);

        return $this->issuesSorter->sortIssues($issues);
    }

    private function prepareIssues(string $repository, int $milestoneId): array
    {
        $issues = $this->issueRepository->getByRepositoryAndMilestoneId($repository, $milestoneId);
        /** @var Issue $singleIssue */
        foreach ($issues as $singleIssue) {
            if ($singleIssue->isSetPullRequest()) {
                continue;
            }

            $issues[$this->getKanbanState($singleIssue)][] = new IssueViewModel($singleIssue);
        }

        return $issues;
    }

    private function getKanbanState(Issue $issue): string
    {
        if ($issue->getState() === Issue::STATE_CLOSED)
            return KanbanIssueStatus::COMPLETED();
        else if (null != $issue->getAssignee())
            return KanbanIssueStatus::ACTIVE();
        else
            return KanbanIssueStatus::QUEUED();
    }
}
