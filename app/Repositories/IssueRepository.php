<?php

namespace App\Repositories;

use App\Api\GithubClient;
use App\Models\Issue;

class IssueRepository
{
    private $githubClient;

    public function __construct(GithubClient $githubClient)
    {
        $this->githubClient = $githubClient;
    }

    public function getByRepositoryAndMilestoneId(string $repositoryName, int $milestoneId): array
    {
        $result = $this->githubClient->getIssues($repositoryName, $milestoneId);
        $issues = [];
        foreach ($result as $issue) {
            $issues[] = new Issue(
                $issue
            );
        }
     
        return $issues;
    }
}