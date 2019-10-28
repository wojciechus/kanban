<?php

namespace App;

use App\Environment\EnvironmentResolver;
use App\Models\Container;

class Application
{
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
