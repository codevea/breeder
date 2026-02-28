<?php

namespace App\Repository;


use App\Repository\ActivityRepository;
use App\Repository\RaceCatRepository;
use App\Repository\RaceDogRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class GlobalsRepository extends AbstractExtension implements GlobalsInterface
{
    public function __construct(private readonly ActivityRepository $activityRepository, private readonly RaceCatRepository $raceCatRepository, private readonly RaceDogRepository $raceDogRepository) {}

    public function getGlobals(): array
    {
        
        return [
            'globalsActvityFindAll' => $this->activityRepository->findAll(),
            'globalsRaceCatsFindAll' => $this->raceCatRepository->findAll(),
            'globalsRaceDogsFindAll' => $this->raceDogRepository->findAll(),
        ];
    }
}
