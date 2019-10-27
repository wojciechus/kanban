<?php

namespace App;

use App\Api\GithubClient;
use App\Models\Issue;
use App\Repositories\IssueRepository;
use App\Repositories\MilestoneRepository;

class Application
{
    private $github;
    private $repositories;
    private $pausedLabels;
    private $milestoneRepository;
    private $issueRepository;

    public function __construct(
        GithubClient $github,
        MilestoneRepository $milestoneRepository,
        IssueRepository $issueRepository,
        array $repositories,
        array $paused_labels = []
    ) {
        $this->github = $github;
        $this->repositories = $repositories;
        $this->pausedLabels = $paused_labels;
        $this->milestoneRepository = $milestoneRepository;
        $this->issueRepository = $issueRepository;
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
            $issues = $this->issues($milestone->getRepository(), $milestone->getNumber());
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

        return $milestonesViewModel;
    }

    private function issues(string $repository, int $milestoneId): array
    {
        $issues = $this->issueRepository->getByRepositoryAndMilestoneId($repository, $milestoneId);
        /** @var Issue $singleIssue */
        foreach ($issues as $singleIssue) {
            if ($singleIssue->isSetPullRequest()) {
                continue;
            }
            $issues[$this->getState($singleIssue)][] = [
                'id' => $singleIssue->getId(),
                'number' => $singleIssue->getNumber(),
                'title' => $singleIssue->getTitle(),
                'body' => $singleIssue->getBody(),
                'url' => $singleIssue->getUrl(),
                'assignee' => $singleIssue->getAssignee(),
                'paused' => $singleIssue->isPaused(),
                'closed' => $singleIssue->getClosed(),
            ];
        }

        return $this->sortIssues($issues);
    }

    private function sortIssues(array $issues): array
    {
        if (!empty($issues['active'])) {
            usort($issues['active'], function (array $a, array $b) {
                if ($a['paused'] === $b['paused']) {
                    return strcmp($a['title'], $b['title']);
                }

                return $a['paused'] ? -1 : 1;
            });
        }

        return $issues;
    }

    private function getState(Issue $issue): string
    {
        if ($issue->getState() === 'closed')
            return 'completed';
        else if (null != $issue->getAssignee())
            return 'active';
        else
            return 'queued';
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
