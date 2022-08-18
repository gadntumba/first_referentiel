<?php

namespace App\Controller;

use App\Entity\Productor;

class ProductorStockRainsingActivitiesController
{
    public function __invoke(Productor $data): Productor
    {
        $data->setStockRainsingActivities;
        return $data;
    }
}