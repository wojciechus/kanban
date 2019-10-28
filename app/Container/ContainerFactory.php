<?php

namespace App\Container;

use App\Api\GithubClient;
use App\Environment\EnvironmentResolver;
use App\Models\Container;
use App\Providers\IssueProvider;
use App\Providers\KanbanProvider;
use App\Providers\MilestoneProvider;
use App\Repositories\IssueRepository;
use App\Repositories\MilestoneRepository;
use App\Services\FulfillingCalculator;
use App\Services\IssuesSorter;

class ContainerFactory
{
    private $githubClient;
    private $milestoneRepository;
    private $issueRepository;
    private $issueProvider;
    private $issueSorter;
    private $milestoneProvider;
    private $fulfillingCalculator;
    private $kanbanProvider;

    public function getContainer(string $token): Container
    {
        $this->prepareServices($token);

        $container = new Container(
            $this->githubClient,
            $this->milestoneRepository,
            $this->milestoneProvider,
            $this->issueRepository,
            $this->issueProvider,
            $this->issueSorter,
            $this->fulfillingCalculator,
            $this->kanbanProvider
        );

        return $container;
    }

    private function prepareServices(string $token): void
    {
        $this->githubClient = new GithubClient($token, EnvironmentResolver::env('GH_ACCOUNT'));
        $this->milestoneRepository = new MilestoneRepository($this->githubClient);
        $this->milestoneProvider = new MilestoneProvider($this->milestoneRepository);
        $this->issueRepository = new IssueRepository($this->githubClient);
        $this->issueSorter = new IssuesSorter();
        $this->issueProvider = new IssueProvider($this->issueRepository, $this->issueSorter);
        $this->fulfillingCalculator = new FulfillingCalculator();
        $this->kanbanProvider = new KanbanProvider($this->issueProvider, $this->fulfillingCalculator);
    }
}