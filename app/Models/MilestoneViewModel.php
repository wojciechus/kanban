<?php

namespace App\Models;

class MilestoneViewModel
{
    private $milestone;
    public $url;
    private $progress;
    private $queued;
    private $active;
    private $completed;

    public function __construct(
        $milestone,
        $url,
        $progress,
        $queued,
        $active,
        $completed
    ) {

        $this->milestone = $milestone;
        $this->url = $url;
        $this->progress = $progress;
        $this->queued = $queued;
        $this->active = $active;
        $this->completed = $completed;
    }

    /**
     * @return mixed
     */
    public function getMilestone()
    {
        return $this->milestone;
    }

    /**
     * @param mixed $milestone
     */
    public function setMilestone($milestone): void
    {
        $this->milestone = $milestone;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * @param mixed $progress
     */
    public function setProgress($progress): void
    {
        $this->progress = $progress;
    }

    /**
     * @return mixed
     */
    public function getQueued()
    {
        return $this->queued;
    }

    /**
     * @param mixed $queued
     */
    public function setQueued($queued): void
    {
        $this->queued = $queued;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active): void
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * @param mixed $completed
     */
    public function setCompleted($completed): void
    {
        $this->completed = $completed;
    }

}