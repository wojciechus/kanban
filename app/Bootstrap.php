<?php

namespace App;

use App\Api\GithubClient;
use App\Authentication\Authentication;
use App\Container\ContainerFactory;
use App\Environment\EnvironmentResolver;
use App\Providers\IssueProvider;
use App\Providers\MilestoneProvider;
use App\Repositories\IssueRepository;
use App\Repositories\MilestoneRepository;
use App\Services\FulfillingCalculator;
use App\Services\IssuesSorter;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class Bootstrap
{
    public const PAUSED_LABELS = ['waiting-for-feedback'];
    private const VIEWS_DIR = '../app/Views';

    private $authentication;
    private $mustacheEngine;
    private $containerFactory;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
        $this->mustacheEngine = new Mustache_Engine(
            [
                'loader' => new Mustache_Loader_FilesystemLoader(self::VIEWS_DIR),
            ]
        );
        $this->containerFactory = new ContainerFactory();
    }

    public function run(): void
    {
        $token = $this->authentication->login();
        $container = $this->containerFactory->getContainer($token);
        $application = new Application($container);

        echo $this->mustacheEngine->render(
            'index',
            [
                'milestones' => $application->board(),
            ]
        );
    }
}