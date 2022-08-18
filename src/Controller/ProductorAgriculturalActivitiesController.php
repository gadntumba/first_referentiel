<?php

namespace App\Controller;

use App\Entity\Productor;

class ProductorAgriculturalActivitiesController
{
    public function __invoke(Productor $data): Productor
    {
        $data->setAgriculturalActivities;
        return $data;
    }
}