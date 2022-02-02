<?php

namespace App\Controller;

use App\Entity\Productor;

class ProductorStockRainsingActivitiesTypesController
{
    public function __invoke(Productor $data): Productor
    {
        $data->setStockRainsingActivitiesTypes;
        return $data;
    }
}