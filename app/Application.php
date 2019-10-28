<?php

namespace App;

use App\Api\GithubClient;
use App\Enum\KanbanIssueStatus;
use App\Environment\EnvironmentResolver;
use App\Models\Container;
use App\Models\MilestoneViewModel;
use App\Providers\IssueProvider;
use App\Providers\MilestoneProvider;
use App\Repositories\MilestoneRepository;
use App\Services\FulfillingCalculator;

class Application
{
    private $githubClient;
    private $milestoneRepository;
    private $issueProvider;
    private $fulfillingCalculator;
    private $milestoneProvider;
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function board(): array
    {
        $repositories = explode('|', EnvironmentResolver::env('GH_REPOSITORIES'));
        $milestones = $this->container->getMilestoneProvider()->getAllWithRepositories($repositories);

        return $this->container->getKanbanProvider()->getKanbanFeed($milestones);
    }
}
