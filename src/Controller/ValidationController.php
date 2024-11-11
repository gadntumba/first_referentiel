<?php

namespace App\Controller;

use App\Services\ManagerMakeValidateFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class ValidationController extends AbstractController 
{
    const CITIES_NAMES = ["bukavu", "bunia", "goma", "kananga", "kin", "matadi", "mbujimayi"];

    Const MODES = ["certe", "approx"];

    public function __construct(private ManagerMakeValidateFile $managerMakeValidateFile) 
    {
        
    }
    /**
     * @Route("/validated/download", methods={"GET","HEAD"}, name="validated_download")
     */
    function downloadFile(Request $request) : Response 
    {
        $query = $request->query;

        $cityName = $query->get("city");
        $mode = $query->get("mode", "certe");

        if (
            !in_array($cityName, self::CITIES_NAMES) ||
            !in_array($mode, self::MODES) 
        ) 
        {
            throw new HttpException(404, "method or mode not found");
            
        }

        $url = $this->getParameter($cityName."_validation_file_url");
        //dump($cityName);
        //dump($mode);
        //dd($url);

        $cityData = $this->managerMakeValidateFile->getCityData($url);
        $assets = $this->managerMakeValidateFile->getNotValidatedData();
        
        if ($mode == "certe") {
            $filters = $this->managerMakeValidateFile->getCerteData($cityData, $assets); 
        }else {
            $filters = $this->managerMakeValidateFile->getApproximativeData($cityData, $assets);
        }

        $props = [
            "Nom",
            "Postnom",
            "Prénom",
            "Téléphone 1",
            "Téléphone 2",
            "Téléphone 3"
        ];
        
        $resultArr = [];
        
        array_push($resultArr, implode(",", $props) );
        
        foreach ($filters as $key => $it) {
        
            //var_dump($itemPhonenumber["phoneNumber"]);
            //var_dump($itemPhonenumber?$itemPhonenumber["phoneNumber"]:"");
            //$itemPhonenumber?$itemPhonenumber["phoneNumber"]:""
            //die();
            $resIt = implode(",",[
                $it["name"],
                $it["lastname"],
                $it["firstname"],
                $it["phone1"],
                $it["phone2"],
                $it["phone3"]        
            ]);
        
            array_push(
                $resultArr, 
                $resIt
            );
        }

        $content = implode("\n", $resultArr);
        
        
        $response =  new StreamedResponse(
            function () use ($content) {

                file_put_contents('php://output', $content);
                //$writer->save('php://output');
                //file_put_contents()
            }
        );

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'validated-'. uniqid() . '.csv'
        );

        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
        
    }
    
}
