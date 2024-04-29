<?php
 
namespace App\Services;

use ApiPlatform\Symfony\Validator\Exception\ValidationException;
use App\Entity\MetaDataBrut;
use App\Repository\MetaDataBrutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

//MetaDataBrutService

class MetaDataBrutService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MetaDataBrutRepository $metaDataBrutRepository,
        private ValidatorInterface $validator
    ) 
    {

        
    }

    function create(array $data = []) : MetaDataBrut
    {
        $metaData = new MetaDataBrut();

        $metaData->setFileName(isset($data["fileName"])?$data["fileName"]:null);
        $metaData->setThisSchema(isset($data["thisSchema"])?$data["thisSchema"]:null);
        $metaData->setSource(isset($data["source"])?$data["source"]:null);
        $metaData->setCityName(isset($data["cityName"])?$data["cityName"]:null);
        $metaData->setOtherContent(isset($data["otherContent"])?$data["otherContent"]:null);
        $metaData->setOtherContent2(isset($data["otherContent2"])?$data["otherContent2"]:null);

        $metaData->setCheetTitle(isset($data["cheetTitle"])?$data["cheetTitle"]:null);

        $violationList = $this->validator->validate($metaData);


        if ($violationList->count() > 0) {
            throw new ValidationException($violationList);
        }
        
        return $metaData;
    }
    
}
