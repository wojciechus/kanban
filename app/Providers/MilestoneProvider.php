<?php

namespace App\Providers;

use App\Repositories\MilestoneRepository;

class MilestoneProvider
{
    private $milestoneRepository;

    public function __construct(MilestoneRepository $milestoneRepository)
    {
        $this->milestoneRepository = $milestoneRepository;
    }

    public function getAllWithRepositories(array $repositories): array
    {
        $milestones = [];
        foreach ($repositories as $repository) {
            $singleRepositoryMilestones = $this->milestoneRepository->getByRepository($repository);
            foreach ($singleRepositoryMilestones as &$singleMilestone) {
                $singleMilestone->setRepository($repository);
            }

            $milestones = array_merge($milestones, $singleRepositoryMilestones);
        }
        
        return $milestones;
    }
}
