<?php

namespace App\Message;

final class SendLoadSubscriberInAgromwinda
{
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */

     private $id;

     public function __construct(string $id)
     {
         $this->id = $id;
     }

    public function getId(): string
    {
        return $this->id;
    }
}
