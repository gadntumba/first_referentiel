<?php

namespace App\Controller;

use App\Entity\Productor;

class ProductorFichingActivityController
{
    public function __invoke(Productor $data): Productor
    {
        $data->getFichingactivity();
        return $data;
    }
}