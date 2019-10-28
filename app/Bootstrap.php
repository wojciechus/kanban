<?php

namespace App;

use App\Api\GithubClient;
use App\Authentication\Authentication;
use App\Environment\EnvironmentResolver;
use App\Providers\IssueProvider;
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
    private $repositories;
    private $mustacheEngine;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
        $this->mustacheEngine = new Mustache_Engine(
            [
                'loader' => new Mustache_Loader_FilesystemLoader(self::VIEWS_DIR),
            ]
        );
        $this->repositories = explode('|', EnvironmentResolver::env('GH_REPOSITORIES'));
    }

    public function run(): void
    {
        $token = $this->authentication->login();
        $githubClient = new GithubClient($token, EnvironmentResolver::env('GH_ACCOUNT'));
        $milestoneRepository = new MilestoneRepository($githubClient);
        $issueProvider = new IssueProvider(new IssueRepository($githubClient), new IssuesSorter());
        $fulfillingCalculator = new FulfillingCalculator();
        $application = new Application(
            $githubClient,
            $milestoneRepository,
            $issueProvider,
            $fulfillingCalculator,
            $this->repositories
        );

        echo $this->mustacheEngine->render(
            'index',
            [
                'milestones' => $application->board(),
            ]
        );
    }
}