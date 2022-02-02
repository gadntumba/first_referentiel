<?php

namespace App\Controller;

use App\Entity\Productor;

class ProductorSourceController
{
    public function __invoke(Productor $data): Productor
    {
        $data->setSuplyActivities;
        return $data;
    }
}