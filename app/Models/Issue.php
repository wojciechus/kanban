<?php

namespace App\Models;

use App\Bootstrap;
use Michelf\Markdown;

class Issue
{
    private $pullRequest = null;
    private $id;
    private $number;
    private $title;
    private $body;
    private $url;
    private $assignee;
    private $closed;
    private $labels;
    private $state;

    public function __construct(array $issue)
    {
        if (isset($issue['pull_request'])) {
            $this->pullRequest = $issue['pull_request'];
        }
        $this->id = $issue['id'];
        $this->number = $issue['number'];
        $this->title = $issue['title'];
        $this->body = $issue['body'];
        $this->state = $issue['state'];
        $this->url = $issue['html_url'];
        $this->assignee = $issue['assignee'] ?? null;
        $this->closed = $issue['closed_at'];
        $this->labels = $issue['labels'] ?? null;
    }

    public function getPullRequest()
    {
        return $this->pullRequest;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return Markdown::defaultTransform($this->body);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getAssignee(): ?string
    {
        if (!empty($this->assignee)) {
            return sprintf('%s?s=16', $this->assignee['avatar_url']);
        }

        return null;
    }

    public function isPaused(): bool
    {
        if (!empty($this->labels)) {
            foreach ($this->labels as $label) {
                if (in_array($label['name'], Bootstrap::PAUSED_LABELS)) {
                    return true;
                }
            }
        }

        return false;
      }

    public function getClosed(): ?string
    {
        return $this->closed;
    }

    public function isSetPullRequest(): bool
    {
        return (bool)$this->pullRequest;
    }

    public function getLabels(): array
    {
        return $this->labels;
    }

    public function getState(): string
    {
        return $this->state;
    }
}