<?php

namespace App\Controller;

use App\Entity\Productor;

class ProductorProvincesController
{
    public function __invoke(Productor $data): Productor
    {
        $data->getProvinces;
        return $data;
    }
}