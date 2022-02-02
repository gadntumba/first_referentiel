<?php

namespace App\Controller;

use App\Entity\Productor;

class ProductorLevelStudyIdController
{
    public function __invoke(Productor $data): Productor
    {
        $data->setLevelStudy;
        return $data;
        
    }  
  }