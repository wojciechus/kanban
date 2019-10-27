<?php

namespace App\Repositories;

use App\Api\GithubClient;
use App\Models\Issue;

class IssueRepository
{
    private $github;

    public function __construct(GithubClient $github)
    {
        $this->github = $github;
    }

    public function getByRepositoryAndMilestoneId(string $repositoryName, int $milestoneId): array
    {
        $result = $this->github->issues($repositoryName, $milestoneId);
        $issues = [];
        foreach ($result as $issue) {
            $issues[] = new Issue(
                $issue
            );
        }
     
        return $issues;
    }
}