<?php

namespace App\Models;

use App\Api\GithubClient;
use App\Providers\IssueProvider;
use App\Providers\KanbanProvider;
use App\Providers\MilestoneProvider;
use App\Repositories\IssueRepository;
use App\Repositories\MilestoneRepository;
use App\Services\FulfillingCalculator;
use App\Services\IssuesSorter;

class Container
{
    private $githubClient;
    private $milestoneRepository;
    private $milestoneProvider;
    private $issueRepository;
    private $issueProvider;
    private $issueSorter;
    private $fulfillingCalculator;
    private $kanbanProvider;

    public function __construct(
        GithubClient $githubClient,
        MilestoneRepository $milestoneRepository,
        MilestoneProvider $milestoneProvider,
        IssueRepository $issueRepository,
        IssueProvider $issueProvider,
        IssuesSorter $issueSorter,
        FulfillingCalculator $fulfillingCalculator,
        KanbanProvider $kanbanProvider
    )
    {
        $this->githubClient = $githubClient;
        $this->milestoneRepository = $milestoneRepository;
        $this->issueRepository = $issueRepository;
        $this->issueSorter = $issueSorter;
        $this->milestoneProvider = $milestoneProvider;
        $this->fulfillingCalculator = $fulfillingCalculator;
        $this->issueProvider = $issueProvider;
        $this->kanbanProvider = $kanbanProvider;
    }

    public function getGithubClient(): GithubClient
    {
        return $this->githubClient;
    }

    public function getMilestoneRepository(): MilestoneRepository
    {
        return $this->milestoneRepository;
    }

    public function getMilestoneProvider(): MilestoneProvider
    {
        return $this->milestoneProvider;
    }

    public function getIssueRepository(): IssueRepository
    {
        return $this->issueRepository;
    }

    public function getIssueProvider(): IssueProvider
    {
        return $this->issueProvider;
    }

    public function getIssueSorter(): IssuesSorter
    {
        return $this->issueSorter;
    }

    public function getFulfillingCalculator(): FulfillingCalculator
    {
        return $this->fulfillingCalculator;
    }

    public function getKanbanProvider(): KanbanProvider
    {
        return $this->kanbanProvider;
    }
}