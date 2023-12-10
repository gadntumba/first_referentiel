<?php
namespace App\EventListerner;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
// for Doctrine >= 2.4 use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Productor;
use Doctrine\ORM\Events;

class LoadProductorInPlatform implements EventSubscriber
{

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
        ];
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // perhaps you only want to act on some "Product" entity
        if ($entity instanceof Productor) {
            $entityManager = $args->getObjectManager();

            // ... do something with the Product
        }
    }

}