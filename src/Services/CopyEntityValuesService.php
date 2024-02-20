<?php
 
namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class CopyEntityValuesService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function copyValues(mixed $entity1, mixed $entity2)
    {
        if (!is_object($entity1) || !is_object($entity2)) {
            return;
        }
        //dd("ok");
        
        $entity1Reflection = new \ReflectionObject($entity1);
        $entity2Reflection = new \ReflectionObject($entity2);

        foreach ($entity2Reflection->getProperties() as $property) {
            $propertyName = $property->getName();

            // Ignore the ID property
            if (
                $propertyName === 'id' || 
                $propertyName === 'createdAt' || 
                $propertyName === 'updatedAt' || 
                $propertyName === 'deletedAt' || 
                $propertyName === 'slug'
            ) {
                continue;
            }
            
            $accessor = PropertyAccess::createPropertyAccessor();
            //dd($property->getValue($entity2));
                
                $accessor->setValue($entity1, $propertyName, $accessor->getValue($entity2, $propertyName));

            // Check if the property exists in Entity2
            
        }
        //dd($entity1);
        return $entity1;

        // Persist changes
    }
}
