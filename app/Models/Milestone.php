<?php

namespace App\Models;

class Milestone
{
    private $title;
    private $number;
    private $openIssues;
    private $closedIssues;
    private $state;
    private $repository;
    private $htmlUrl;

    public function __construct(
        string $title,
        int $number,
        int $openIssues,
        int $closedIssues,
        string $state,
        string $htmlUrl

    ) {
        $this->title = $title;
        $this->number = $number;
        $this->openIssues = $openIssues;
        $this->closedIssues = $closedIssues;
        $this->state = $state;
        $this->htmlUrl = $htmlUrl;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getOpenIssues(): int
    {
        return $this->openIssues;
    }

    public function getClosedIssues(): int
    {
        return $this->closedIssues;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getRepository(): string
    {
        return $this->repository;
    }

    public function setRepository(string $repository): void
    {
        $this->repository = $repository;
    }

    public function getHtmlUrl(): string
    {
        return $this->htmlUrl;
    }
}