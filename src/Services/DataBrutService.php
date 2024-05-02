<?php
 
namespace App\Services;

use ApiPlatform\Symfony\Validator\Exception\ValidationException;
use App\Entity\DataBrut;
use App\Entity\MetaDataBrut;
use App\Repository\DataBrutRepository;
use App\Repository\MetaDataBrutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

//DataBrutService

class DataBrutService
{
    public function __construct(
        private EntityManagerInterface $em,
        private DataBrutRepository $repository,
        private ValidatorInterface $validator
    ) 
    {

        
    }

    public function create(array $data, MetaDataBrut $metaDataBrut, int $rowId, $source) : DataBrut
    {
        $dataBrut = new DataBrut();
        $dataBrut->setContent($data);
        $dataBrut->setMataData($metaDataBrut);
        $dataBrut->setSource($source);
        $dataBrut->setRowId($rowId);

        //dd($dataBrut);

       /* $dataBrut->setFileName(isset($dataBrut["fileName"])?$dataBrut["fileName"]:null);
        $dataBrut->setThisSchema(isset($dataBrut["thisSchema"])?$dataBrut["thisSchema"]:null);
        $dataBrut->setSource(isset($dataBrut["source"])?$dataBrut["source"]:null);
        $dataBrut->setCityName(isset($dataBrut["cityName"])?$dataBrut["cityName"]:null);
        $dataBrut->setOtherContent(isset($dataBrut["otherContent"])?$dataBrut["otherContent"]:null);
        $dataBrut->setOtherContent2(isset($dataBrut["otherContent2"])?$dataBrut["otherContent2"]:null);

        $dataBrut->setCheetTitle(isset($dataBrut["cheetTitle"])?$dataBrut["cheetTitle"]:null);*/

        $violationList = $this->validator->validate($dataBrut);
        

        if ($violationList->count() > 0) {
            throw new ValidationException($violationList);
        }
        //dd($dataBrut);
        
        $this->em->persist($dataBrut);
        
        //$this->em->flush();
        
        return $dataBrut;
    }

    public function getListUCP() : array 
    {
        $me = $this;
        $data = $this->repository->findBy([],null,30); 

        return array_map(
            function (DataBrut $dataBrut) use($me) {
                return $me->transform($dataBrut);
            },
            $data
        );
        
    }
    public function getListMetaData(MetaDataBrut $metadata) : array 
    {
        $me = $this;
        $data = $this->repository->findBy(["mataData" => $metadata],null,30); 

        return array_map(
            function (DataBrut $dataBrut) use($me) {
                return $me->transform($dataBrut);
            },
            $data
        );
        
    }

    public function transform(DataBrut $dataBrut) : array 
    {
        $metaDataBrut = $dataBrut->getMataData();
        //["cityName","fileName","source","cheetTitle"]
        return [
            "data" => $dataBrut->getContent(),
            "rowId" => $dataBrut->getRowId(),
            "source" => $metaDataBrut->getSource(),
            "thisSchema" => $metaDataBrut->getThisSchema(),
            "fileName" => $metaDataBrut->getFileName(),
            "cheetTitle" => $metaDataBrut->getCheetTitle(),
            "cityName" => $metaDataBrut->getCityName(),
        ];
        
    }

    public function search(
        MetaDataBrut $mataData, int $rowId
    ) : ?DataBrut 
    {
        $criteria = compact("mataData","rowId");
        
        //dd($criteria);

        return $this->repository->findOneBy($criteria);
        
    }

    public function lastRow(
        MetaDataBrut $mataData
    )  
    {
        //$criteria = compact("mataData","rowId");
        
        //dd($criteria);

        $res = $this->repository->findLasRowId($mataData);

        if (is_null($res)) {
            return 0;            
        }

        return array_pop($res);
        
    }
    
}
