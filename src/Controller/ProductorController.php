<?php

namespace App\Controller;

use App\Entity\Productor;

class ProductorController
{
    public function __invoke(Productor $data): Productor
    {
        $data->setProductor;
        return $data;
    }
}