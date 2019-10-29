<?php

namespace App\Repositories;

use App\Api\GithubClient;
use App\Models\Milestone;

class MilestoneRepository
{
    private $githubClient;

    public function __construct(GithubClient $githubClient)
    {
        $this->githubClient = $githubClient;
    }

    public function getByRepository(string $repositoryName): array
    {
        $result = $this->githubClient->getMilestones($repositoryName);
        $milestones = [];
        foreach ($result as $milestone) {
            $milestones[] = new Milestone(
                $milestone['title'],
                $milestone['number'],
                $milestone['open_issues'],
                $milestone['closed_issues'],
                $milestone['state'],
                $milestone['html_url']
            );
        }
     
        return $milestones;
    }
}