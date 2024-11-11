<?php
namespace App\EventListerner;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
// for Doctrine >= 2.4 use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Productor;
use App\Message\SendLoadSubscriberInAgromwinda;
use Doctrine\ORM\Events;
use Symfony\Component\Messenger\MessageBusInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;

#[AsDoctrineListener(event: Events::postPersist, priority: 500, connection: 'default')]
class SearchIndexer
{
    public function __construct(private MessageBusInterface $bus) {
        
    }

        // the listener methods receive an argument which gives you access to
    // both the entity object of the event and the entity manager itself
    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();

        // if this listener only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!($entity instanceof Productor)) {
            return;
        }

        //$entityManager = $args->getObjectManager();
        $this->bus->dispatch(
            new SendLoadSubscriberInAgromwinda($entity->getId())
        );
        // ... do something with the Product entity
    }


}