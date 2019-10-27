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
    private $milestoneApi;
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
        $this->milestoneApi = $this->client->api(self::ISSUES_KEY)->milestones();
    }

    public function milestones(string $repository): array
    {
        return $this->milestoneApi->all($this->account, $repository);
    }

    public function issues(string $repository, int $milestoneId): array
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