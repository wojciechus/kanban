<?php

namespace App\Models;

class IssueViewModel
{
    /**
     * @var string|null
     */
     public $closed;
    /**
     * @var int
     */
    public $id;
    /**
     * @var int
     */
    public $number;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $body;
    /**
     * @var string
     */
    public $url;
    /**
     * @var string|null
     */
    public $assignee;
    /**
     * @var bool
     */
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

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return bool
     */
    public function isPaused(): bool
    {
        return $this->paused;
    }
}