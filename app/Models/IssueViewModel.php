<?php

namespace App\Models;

class IssueViewModel
{
    public $closed;
    public $id;
    public $number;
    public $title;
    public $body;
    public $url;
    public $assignee;
    public $paused;

    public function __construct(Issue $singleIssue)
    {
        $this->id = $singleIssue->getId();
        $this->number = $singleIssue->getNumber();
        $this->title = $singleIssue->getTitle();
        $this->body = $singleIssue->getBody();
        $this->url = $singleIssue->getUrl();
        $this->assignee = $singleIssue->getAssignee();
        $this->paused = $singleIssue->isPaused();
        $this->closed = $singleIssue->getClosed();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function isPaused(): bool
    {
        return $this->paused;
    }
}