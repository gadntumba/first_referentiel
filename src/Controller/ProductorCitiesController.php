<?php

namespace App\Controller;

use App\Entity\Productor;

class ProductorCitiesController
{
    public function __invoke(Productor $data): Productor
    {
        $data->setCities;
        return $data;
    }
}