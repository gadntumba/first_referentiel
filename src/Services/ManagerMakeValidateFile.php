<?php
 
namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\Entity\Productor;
use App\Repository\ProductorRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\String\Slugger\SluggerInterface;

class ManagerMakeValidateFile 
{
    const INDEXS = ["name", "firstname", "lastname"];
    const CITIES_NAMES = ["bukavu", "bunia", "goma", "kananga", "kin", "matadi", "mbujimayi"];

    Const MODES = ["certe", "approx"];

    public function __construct(
        private FileUploader $fileUploader,
        private EntityManagerInterface $em,
        private ContainerBagInterface $containerBag,
        private SluggerInterface $slugger
    ) 
    {
        
    }

    public function makeFile(string $cityName, string $mode="certe", string $dirName="/var/www") : void 
    {

        if (
            !in_array($cityName, self::CITIES_NAMES) ||
            !in_array($mode, self::MODES) 
        ) 
        {
            throw new HttpException(404, "method or mode not found");
        }

        $url = $this->containerBag->get($cityName."_validation_file_url");
        //dump($cityName);
        //dump($mode);
        //dd($url);

        $cityData = $this->getCityData($url);
        $assets = $this->getNotValidatedData();
        
        if ($mode == "certe") {
            $filters = $this->getCerteData($cityData, $assets); 
        }else {
            $filters = $this->getApproximativeData($cityData, $assets);
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
        
        foreach ($filters as $key => $it) 
        {
        
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

        $sufix = $this->slugger->slug((new \DateTime())->format(\DateTimeInterface::RFC3339_EXTENDED));

        $dir = $this->containerBag->get("kernel.project_dir")."/var";

        $filePath = $dir ."/". $cityName . "-" . $mode . "-" .  $sufix . ".csv";

        //%kernel.project_dir%/var/
        file_put_contents($filePath, $content);        
    }

    public function getCerteData(array $cityData, array $notValidedData) 
    {
        $indexs = self::INDEXS;
        $me = $this;

        $filters = array_filter(
            $cityData,
            function ($item) use($indexs, $notValidedData, $me) 
            {
                return $me->itemMayBeExist($item, $notValidedData, $indexs);
            }
        );

        return $filters;
        
    }

    public function getApproximativeData(array $cityData, array $notValidedData) 
    {   
        $indexs = self::INDEXS;
        $me = $this;

        $filters = array_filter(
            $cityData,
            function ($item) use($indexs, $notValidedData, $me) 
            {
                return $me->itemMayBeExist($item, $notValidedData, $indexs, 4);
            }
        );

        return $filters;
        
    }

    public function getCityData(string $url) : array 
    {
        $path = $this->downloadDataAndSave($url);
        
        $reader = new Xlsx();
        $spreadsheet = $reader->load($path);
        //var_dump("ok");
        //die();
        //$spreadsheet->addSheet();
        $sheets = $spreadsheet->getAllSheets();
        $w = new Worksheet();
        //$w->setC;
        $sheet = $sheets[0];
        $countRow = $sheet->getHighestRow();

        $cityData = [];

        for ($i=2; $i < $countRow; $i++) { 
            $el = [];
           // $el["code"] = trim($sheet->getCell("A".$i)->getValue());
           //var_dump($sheet->getCell("B".$i)->getValue());
           //die();
            $el["row"] = $i;
            $el["name"] = trim($sheet->getCell("A".$i)->getValue());
            $el["lastname"] = trim($sheet->getCell("B".$i)->getValue());
            $el["firstname"] = trim($sheet->getCell("C".$i)->getValue());
            $el["phone1"] = trim($sheet->getCell("D".$i)->getValue());
            $el["phone2"] = trim($sheet->getCell("E".$i)->getValue());
            $el["phone3"] = trim($sheet->getCell("F".$i)->getValue());

            array_push($cityData, $el);
            
        }

        return $cityData;
        
    }

    function getNotValidatedData() : array 
    {
        /**
         * @var ProductorRepository
         */
        $productorRepository = $this->em->getRepository(Productor::class);
        //findByNotValidated
        $data = $productorRepository->findByNotValidated();

        $assets = array_map(
            function (Productor $item) {
                $el = [];
                $el["name"] = $item->getName();
                $el["lastname"] = $item->getLastName();
                $el["firstname"] = $item->getFirstName();
                $el["town"] = $item->getHousekeeping()?->getAddress()?->getTown()?->getName();
                $el["city"] = $item->getHousekeeping()?->getAddress()?->getTown()?->getCity()?->getName();
                $el["validation"] = "non valide";
                return $el;                
            },
            $data
        );

        return $assets;
        
    }

    public function downloadDataAndSave(string $url) : string 
    {
        $stream = $this->fileUploader->downloadStreamGoogle($url);

        $path = "/tmp/". uniqid("agrodata-").".xlsx";

        file_put_contents($path, $stream->getContents());

        return $path;
        
    }
    // private 
    private function fctRetirerAccents($varMaChaine)
    {
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        //Préférez str_replace à strtr car strtr travaille directement sur les octets, ce qui pose problème en UTF-8
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');

        $varMaChaine = str_replace($search, $replace, $varMaChaine);
        return $varMaChaine; //On retourne le résultat
    }


    private function distance(array $arr1, array $arr2, $indexs) : int {
        $dists = [];

        foreach ($indexs as $key => $index) {
            $value1 = $arr1[$index]??null;
            foreach ($indexs as $key => $index2) {
                $value2 = $arr2[$index]??null;
                array_push($dists, levenshtein(strtolower($this->fctRetirerAccents($value1)), strtolower($this->fctRetirerAccents($value2)) ));
            }
        }

        $min = array_reduce(
            $dists,
            function ($prev, $curr)  {
                return $prev > $curr ? $curr : $prev;
            },
            100000
        );

        return $min;
    }

    private function makeArr(array $tab) {
        $possibilityArrModel = [[0,1],[0,2], [1,0], [2,0], [1,2], [2,1]];
        $arr = [];

        foreach ($possibilityArrModel as $key => $indexs) {
            array_push($arr, [$tab[$indexs[0]],$tab[$indexs[1]]] );
        }

        return $arr;
    }

    private function makeArrAll(array $tab, array $indexs) {
        $arrs = $this->makeArr($indexs);
        $arr = [];

        foreach ($arrs as $key => $indexs) {
            array_push($arr, $tab[$indexs[0]].$tab[$indexs[1]] );
        }
        return $arr;
        
    }

    private function makeCartesienProduct(array $tab1, array $tab2) {
        $possibilityCartesienModel = [[0,0],[0,1], [0,2], [1,0], [1,1], [1,2], [2,0], [2,1], [2,2]];
        $arr = [];

        foreach ($possibilityCartesienModel as $key => $indexs) {

            array_push($arr, [$tab1[$indexs[0]],$tab2[$indexs[1]]]  );

        }

        return $arr;
    }

    private function distanceProduct(array $product) {
        $dists = [];

        foreach ($product as $key => $item) 
        {

            array_push($dists, levenshtein(strtolower($this->fctRetirerAccents($item[0])), strtolower($this->fctRetirerAccents($item[1])) ));

            
        }
        //return $dists;

        $min = array_reduce(
            $dists,
            function ($prev, $curr)  {
                return $prev > $curr ? $curr : $prev;
            },
            100000
        );

        return $min;
    }

    private function distanceArr(array $first, array $last, array $indexs)  
    {
        $arrs1 = $this->makeArrAll($first, $indexs);
        $arrs2 = $this->makeArrAll($last, $indexs);
        
        $cpArr =$this->makeCartesienProduct($arrs1, $arrs2);
        return $this->distanceProduct ($cpArr);
        
    }

    private function getFirestFilter(array $item, array $assets, array $indexs) {
        $filterDist = [];

        foreach ($assets as $key => $value) {
            if (3 >= $this->distance($value, $item, $indexs) ) {
                //$filterDist
                array_push($filterDist, $value);
            }
        }

        return $filterDist;
    }

    private function itemMayBeExistFileter(array $item, array $filterDist, array $indexs, int $pricision = 1) {
        
        //$filterDist2 = [];
        foreach ($filterDist as $key => $value) {
            if ($pricision >= $this->distanceArr($value, $item, $indexs) ) {
                //$filterDist
                return true;
                //array_push($filterDist2, $value);
            }
        }
        return false;
    }

    private function itemMayBeExist(array $item, array $assets, array $indexs, int $precision=1) {
        $filters = $this->getFirestFilter($item, $assets, $indexs);
        return $this->itemMayBeExistFileter($item, $filters, $indexs, $precision);
    }




    // en private



}