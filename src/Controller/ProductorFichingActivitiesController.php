<?php

namespace App\Controller;

use App\Entity\Productor;

class ProductorFichingActivitiesController
{
    public function __invoke(Productor $data): Productor
    {
        $data->setFichingActivities;
        return $data;
    }
}