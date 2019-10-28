<?php

namespace App;

use App\Api\GithubClient;
use App\Enum\KanbanIssueStatus;
use App\Models\MilestoneViewModel;
use App\Providers\IssueProvider;
use App\Repositories\MilestoneRepository;
use App\Services\FulfillingCalculator;

class Application
{
    private $githubClient;
    private $repositories;
    private $milestoneRepository;
    private $issueProvider;
    private $fulfillingCalculator;

    public function __construct(
        GithubClient $githubClient,
        MilestoneRepository $milestoneRepository,
        IssueProvider $issueProvider,
        FulfillingCalculator $fulfillingCalculator,
        array $repositories
    )
    {
        $this->githubClient = $githubClient;
        $this->repositories = $repositories;
        $this->milestoneRepository = $milestoneRepository;
        $this->issueProvider = $issueProvider;
        $this->fulfillingCalculator = $fulfillingCalculator;
    }

    public function board(): array
    {
        $milestones = [];
        foreach ($this->repositories as $repository) {
            $repositoryMilestones = $this->milestoneRepository->getByRepository($repository);
            foreach ($repositoryMilestones as &$singleMilestone) {
                $singleMilestone->setRepository($repository);
            }

            $milestones = array_merge($milestones, $repositoryMilestones);
        }

        ksort($milestones);
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
}
