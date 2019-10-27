<?php

namespace App;

use App\Api\GithubClient;
use App\Providers\IssueProvider;
use App\Repositories\MilestoneRepository;

class Application
{
    private $githubClient;
    private $repositories;
    private $milestoneRepository;
    private $issueProvider;

    public function __construct(
        GithubClient $githubClient,
        MilestoneRepository $milestoneRepository,
        IssueProvider $issueProvider,
        array $repositories
    ) {
        $this->githubClient = $githubClient;
        $this->repositories = $repositories;
        $this->milestoneRepository = $milestoneRepository;
        $this->issueProvider = $issueProvider;
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
            $percent = $this->percent($milestone->getClosedIssues(), $milestone->getOpenIssues());
            if ($percent) {
                $milestonesViewModel[] = [
                    'milestone' => $milestone->getTitle(),
                    'url' => $milestone->getHtmlUrl(),
                    'progress' => $percent,
                    'queued' => $issues['queued'],
                    'active' => $issues['active'],
                    'completed' => $issues['completed'],
                ];
            }
        }

        return $milestonesViewModel ?? [];
    }

    private function percent(int $complete, int $remaining): array
    {
        $total = $complete + $remaining;
        if ($total > 0) {
            $percent = ($complete || $remaining) ? round($complete / $total * 100) : 0;

            return [
                'total' => $total,
                'complete' => $complete,
                'remaining' => $remaining,
                'percent' => $percent
            ];
        }

        return [];
    }
}
