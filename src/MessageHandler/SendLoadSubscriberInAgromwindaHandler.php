<?php

namespace App\MessageHandler;

use App\Entity\Productor;
use App\Message\SendLoadSubscriberInAgromwinda;
use App\Repository\ProductorRepository;
use App\Services\ManagerLoadSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SendLoadSubscriberInAgromwindaHandler implements MessageHandlerInterface
{
    public function __construct(
        private ManagerLoadSubscriber $manager,
        private EntityManagerInterface $em

    ) 
    {
        
    }

    public function __invoke(SendLoadSubscriberInAgromwinda $message)
    {
        
        /**
         * @var ProductorRepository
         */
        $repository = $this->em->getRepository(Productor::class);

        $entity = $repository->find($message->getId());

        if ($entity) {
            $this->manager->load($entity);
        }
    }
}
