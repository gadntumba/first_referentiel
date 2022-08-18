<?php

namespace App\Controller;

use App\Entity\Productor;

class ProductorTownController
{
    public function __invoke(Productor $data): Productor
    {
        $data->setTown;
        return $data;
    }
}