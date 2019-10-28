<?php

namespace App\Providers;

use App\Enum\KanbanIssueStatus;
use App\Models\MilestoneViewModel;
use App\Services\FulfillingCalculator;

class KanbanProvider
{
    private $issueProvider;
    private $fulfillingCalculator;

    public function __construct(IssueProvider $issueProvider, FulfillingCalculator $fulfillingCalculator)
    {
        $this->issueProvider = $issueProvider;
        $this->fulfillingCalculator = $fulfillingCalculator;
    }

    public function getKanbanFeed(array $milestones): array
    {
        $milestones = $this->sort($milestones);
        foreach ($milestones as $milestone) {
            $issues = $this->issueProvider->getIssues($milestone->getRepository(), $milestone->getNumber());
            $percent = $this->fulfillingCalculator->percent($milestone->getClosedIssues(), $milestone->getOpenIssues());
            if ($percent) {
                $milestonesViewModels[] = new MilestoneViewModel(
                    $milestone->getTitle(),
                    $milestone->getHtmlUrl(),
                    $percent,
                    $issues[KanbanIssueStatus::QUEUED],
                    $issues[KanbanIssueStatus::ACTIVE],
                    $issues[KanbanIssueStatus::COMPLETED]
                );
            }
        }
        return $milestonesViewModels ?? [];
    }

    public function sort(array $milestones): array
    {
        ksort($milestones);

        return $milestones;
    }
}
