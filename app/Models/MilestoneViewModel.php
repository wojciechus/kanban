<?php

namespace App\Models;

class MilestoneViewModel
{
    public $milestone;
    public $url;
    public $progress;
    public $queued;
    public $active;
    public $completed;

    public function __construct(
        $titleMilestone,
        $url,
        $progress,
        $queued,
        $active,
        $completed
    )
    {
        $this->milestone = $titleMilestone;
        $this->url = $url;
        $this->progress = $progress;
        $this->queued = $queued;
        $this->active = $active;
        $this->completed = $completed;
    }

    public function getMilestone(): string
    {
        return $this->milestone;
    }

    public function setMilestone(string $milestone): void
    {
        $this->milestone = $milestone;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getProgress(): ?array
    {
        return $this->progress;
    }

    public function setProgress(?array $progress): void
    {
        $this->progress = $progress;
    }

    public function getQueued(): array
    {
        return $this->queued;
    }

    public function setQueued(array $queued): void
    {
        $this->queued = $queued;
    }

    public function getActive(): array
    {
        return $this->active;
    }

    public function setActive(array $active): void
    {
        $this->active = $active;
    }

    public function getCompleted(): array
    {
        return $this->completed;
    }

    public function setCompleted(array $completed): void
    {
        $this->completed = $completed;
    }
}