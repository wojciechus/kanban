<?php

use KanbanBoard\Application;
use KanbanBoard\Authentication;
use KanbanBoard\GithubClient;
use KanbanBoard\Utilities;

class Bootstrap
{
    public function run(): void
    {
        $repositories = explode('|', Utilities::env('GH_REPOSITORIES'));
        $authentication = new Authentication();
        $token = $authentication->login();
        $github = new GithubClient($token, Utilities::env('GH_ACCOUNT'));
        $board = new Application($github, $repositories, ['waiting-for-feedback']);
        $data = $board->board();
        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader('../views'),
        ));
        echo $m->render('index', array('milestones' => $data));
    }
}