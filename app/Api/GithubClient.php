<?php

namespace App\Api;

use Github\Client;
use Github\HttpClient\CachedHttpClient;

class GithubClient
{
    private const TMP_GITHUB_API_CACHE_DIR = '/tmp/github-api-cache';
    private const ISSUES_KEY = 'issues';
    private const ISSUE_KEY = 'issue';
    private const MILESTONE_STATE_ALL = 'all';

    private $client;
    private $account;

    public function __construct(?string $token, string $account)
    {
        $this->account = $account;
        $this->client = new Client(new CachedHttpClient(
            [
                'cache_dir' => self::TMP_GITHUB_API_CACHE_DIR,
            ]
        ));
        $this->client->authenticate($token, Client::AUTH_HTTP_TOKEN);
    }

    public function getMilestones(string $repository): array
    {
        return $this->client
            ->api(self::ISSUES_KEY)
            ->milestones()
            ->all($this->account, $repository);
    }

    public function getIssues(string $repository, int $milestoneId): array
    {
        $issueParameters = [
            'milestone' => $milestoneId,
            'state' => self::MILESTONE_STATE_ALL,
        ];

        return $this->client
            ->api(self::ISSUE_KEY)
            ->all($this->account, $repository, $issueParameters);
    }
}