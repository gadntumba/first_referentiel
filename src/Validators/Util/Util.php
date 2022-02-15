<?php

namespace App\Validators\Util;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class Util {


    public static function tranformErrorsData(ConstraintViolationListInterface $data)
    {
        $res = [];
        

        foreach ($data as $key => $item) {
            
            if ($item instanceof ConstraintViolationInterface) {
                
                $path = $item->getPropertyPath();
                $value = [
                    "code" => $item->getCode(),
                    "message" => $item->getMessage(),
                ];

                $res[$path] =  isset($res[$path])? [$value, ... $res[$path]] : [$value];
            }

            return $res;
        }
    }
}