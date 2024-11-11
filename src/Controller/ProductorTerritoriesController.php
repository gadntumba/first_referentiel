<?php

namespace App\Controller;

use App\Entity\Productor;

class ProductorTerritoriesController
{
    public function __invoke(Productor $data): Productor
    {
        $data->setTerritories;
        return $data;
    }
}