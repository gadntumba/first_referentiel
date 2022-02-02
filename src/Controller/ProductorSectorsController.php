<?php

namespace App\Controller;

use App\Entity\Productor;

class ProductorSectorsController
{
    public function __invoke(Productor $data): Productor
    {
        $data->setSectors;
        return $data;
    }
}