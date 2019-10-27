<?php

namespace App\Repositories;

use App\Api\GithubClient;
use App\Models\Milestone;

class MilestoneRepository
{
    private $github;

    public function __construct(GithubClient $github)
    {
        $this->github = $github;
    }

    public function getByRepository(string $repositoryName): array
    {
        $result = $this->github->milestones($repositoryName);
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