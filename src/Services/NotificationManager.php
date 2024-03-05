<?php
 
namespace App\Services;

use App\Entity\Observation;
use Pusher\Pusher;

class NotificationManager 
{
    public function __construct(
        private Pusher $pusher
    ) 
    {

        
    }

    /**
     * 
     */
    function notify(Observation $obs) : void 
    {
        $result = $this->pusher->trigger(
            'agrodata', 
            'event-'.$obs->getProductor()?->getInvestigatorId(), 
            [
                "idObs"         => $obs->getId(),
                "sendAt"         => is_null($obs->getSendAt()) ? null : $obs->getSendAt()->format(\DateTimeInterface::RFC3339_EXTENDED),
                "idProd"         => $obs->getProductor()?->getId(),
                "title"         => $obs->getTitle(),
                "content"                   => $obs->getContent(),
                "validatorPhone"         => $obs->getUserId(),
                "productor"         => [
                    "names" => $obs->getProductor()?->getName() . " " . $obs->getProductor()?->getFirstName(). " " . $obs->getProductor()?->getLastName(),
                    "phoneNumber" => $obs->getProductor()?->getPhoneNumber()
                ],
            ]
        );
        
    }
}