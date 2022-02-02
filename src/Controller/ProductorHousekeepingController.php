<?php

namespace App\Controller;

use App\Entity\Productor;

class ProductorHousekeepingController
{
    public function __invoke(Productor $data): Productor
    {
        $data->setHousekeeping;
        return $data;
    }
}