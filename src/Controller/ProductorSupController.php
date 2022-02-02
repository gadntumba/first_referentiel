<?php

namespace App\Controller;

use App\Entity\Productor;

class ProductorSupController
{
    public function __invoke(Productor $data): Productor
    {
        $data->setDeletedAt;
        return $data;
    }
}