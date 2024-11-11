<?php

namespace App\Controller;

use App\Entity\Productor;

class ProductorLevelStudyController
{
    public function __invoke(Productor $data): Productor
    {
        $data->setLevelStudy;
        return $data;
    }
}