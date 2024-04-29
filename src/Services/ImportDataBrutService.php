<?php
 
namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\Entity\Productor;
use App\Repository\ProductorRepository;
use  PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImportDataBrutService 
{
    const INDEXS = ["name", "firstname", "lastname"];
    const CITIES_NAMES = ["bukavu", "bunia", "goma", "kananga", "kin", "matadi", "mbujimayi"];

    Const MODES = ["certe", "approx"];

    const DATA = [
        [
            "name" => "kinshasa",
            "source" => "UCP",
            "filePath" => "data-received-by-ucp/Liste-consolidée-Kinshasa-(1).xlsx",
            "url" => "https://storage.cloud.google.com/agromwinda_platform/liste-consolidee-kinshasa-cop_tri-22_27.xlsx",
        ],
        [
            "name" => "kinshasa",
            "source" => "UCP",
            "filePath" => "data-received-by-ucp/Liste-Consolidée-Kinshasa-COP_Tri 22_27.xlsx",
            "url" => "https://storage.cloud.google.com/agromwinda_platform/liste-consolidee-kinshasa-(1).xlsx",
        ],
        [
            "name" => "mbuji-mayi",
            "source" => "UCP",
            "filePath" => "data-received-by-ucp/Liste-consolidé-MBUJI-MAYI.xlsx",
            "url" => "https://storage.cloud.google.com/agromwinda_platform/liste-consolide-mbuji-mayi.xlsx",
        ],
        [
            "name" => "bukavu",
            "source" => "UCP",
            "filePath" => "data-received-by-ucp/Liste-consolidée-Bukavu-(1).xlsx",
            "url" => "https://storage.cloud.google.com/agromwinda_platform/liste-consolidee-bukavu-(1).xlsx",
        ],
        [
            "name" => "bunia",
            "source" => "UCP",
            "filePath" => "data-received-by-ucp/Liste-consolidée-Bunia-(1).xlsx",
            "url" => "https://storage.cloud.google.com/agromwinda_platform/liste-consolidee-bunia-(1).xlsx",
        ],
        [
            "name" => "goma",
            "source" => "UCP",
            "filePath" => "data-received-by-ucp/Liste-Consolidée-Goma.xlsx",
            "url" => "https://storage.cloud.google.com/agromwinda_platform/liste-consolidee-kananga-(1).xlsx",
        ],
        [
            "name" => "kananga",
            "source" => "UCP",
            "filePath" => "data-received-by-ucp/Liste-consolidée-Kananga-(1).xlsx",
            "url" => "https://storage.cloud.google.com/agromwinda_platform/liste-consolidee-kinshasa-(1).xlsx",
        ],
        [
            "name" => "matadi",
            "source" => "UCP",
            "filePath" => "data-received-by-ucp/Liste-consolidée-Matadi.xlsx",
            "url" => "https://storage.cloud.google.com/agromwinda_platform/liste-consolidee-matadi.xlsx",
        ]
    ];

    public function __construct(
        private FileUploader $fileUploader,
        private EntityManagerInterface $em,
        private ContainerBagInterface $containerBag,
        private SluggerInterface $slugger
    ) 
    {
        
    }

    public function run() : void 
    {
        $citiesFileMatching = self::DATA;

        foreach ($citiesFileMatching as $key => $item) {
            //$allGoma = getData(
            //$allGoma = getCheets(
            //url
            $fileName = $item["filePath"];
            $url = $item["url"];
            $source = $item["source"];
            $cityName = $item["name"];
            
        
            $thisSchema = [
                "organization" => "C",
                //"city" => "L",
                "name" => "F",
                "lastName" => "G",
                "firstName" => "H",
                "phoneNumber" => "I",
                "phoneNumber2" => "J",
                "phoneNumber3" => "K",
                "emailOrganization" => "D",
            ];
        
           // $spreadsheet = getSpreadsheet($fileName);
            $fileName = $this->downloadDataAndSave($url);
            $spreadsheet = $this->getSpreadsheet($fileName);

            $url;
        
            $cheets = $spreadsheet->getAllSheets();
        
            foreach ($cheets as $key => $cheet) {
        
                $allColumns = $this->getColumns(
                    $cheet,
                    $thisSchema,
                    $item["name"],
                    [1, 2]
                );
        
                
        
                $otherContent = array_shift($allColumns);
                $otherContent2 = array_shift($allColumns);
                
                //var_dump($otherContent);
                //var_dump($otherContent2);
                //die();
                $data = [
                    "fileName" => $fileName,
                    "source" => $source,
                    "cityName" => $cityName,
                    "cheetTitle" => $cheet->getTitle(),
                    "thisSchema" => $thisSchema,
                    "otherContent" => $otherContent,
                    "otherContent2" => $otherContent2,
                ];
            
                dd($data);
        
                //$allData = [];    
                //die();
                
            }
            
            //$allGoma = getTransformConsolidateUrl("https://storage.cloud.google.com/agromwinda_platform/bukavu-consolide-65eb86887ed53.xlsx",2);
            /*$itemGoma = $item["name"];
            $itemGoma = $allGoma[2];
            var_dump($itemGoma);*/
            
        }
        
    }

    // en private

    private function getData(Worksheet $sheet, array $matching = [], string $cityName, $firstColumn=9) {
        //$reader = new Xlsx();
        //$spreadsheet = $reader->load(dirname(__DIR__) ."/".$fileName);
        //var_dump("ok");
        //die();
        //$spreadsheet->addSheet();
        //$fileNameList = isset($matching["fileNameList"])?$matching["fileNameList"]:"A";
        $organization = isset($matching["organization"])?$matching["organization"]:"A";
        $city = $cityName;
        $name = isset($matching["name"])?$matching["name"]:"B";
        $lastName = isset($matching["lastName"])?$matching["lastName"]:"C";
        $firstName = isset($matching["firstName"])?$matching["firstName"]:"D";
        $phoneNumber = isset($matching["phoneNumber"])?$matching["phoneNumber"]:"E";
        $phoneNumber2 = isset($matching["phoneNumber2"])?$matching["phoneNumber2"]:"F";
        $phoneNumber3 = isset($matching["phoneNumber3"])?$matching["phoneNumber3"]:"G";
        $emailOrganization = isset($matching["emailOrganization"])?$matching["emailOrganization"]:"H";

        //$sheets = $spreadsheet->getAllSheets();
        //$w = new Worksheet();
        //$w->setC;
        //$sheet = $sheets[1];
        $countRow = $sheet->getHighestRow();
        $countColumn = $sheet->getHighestColumn();
        $colsName = $this->convertToExcelColumn($countColumn);
        $data = [];
        $tampon = [];
        $totalLine=0;
        //$fileNameList = fctRetirerAccents(strtolower($fileName));
        /*var_dump($countRow);
        die();*/
        for ($i=$firstColumn; $i <= $countRow; $i++) {
            
            $el = [];
        // $el["code"] = trim($sheet->getCell("A".$i)->getValue());
        //var_dump($sheet->getCell("B".$i)->getValue());
        //die();
            $el["row"] = $i;
        
            //$el["fileName"] =  $fileNameList;
            $el["organization"] = trim($sheet->getCell($organization.$i)->getValue());
            $el["city"] = $cityName;
            $el["name"] = trim($sheet->getCell($name.$i)->getValue());
            $el["firstName"] = trim($sheet->getCell($firstName.$i)->getValue());
            $el["lastName"] = trim($sheet->getCell($lastName.$i)->getValue());
            $el["phoneNumber"] = trim($sheet->getCell($phoneNumber.$i)->getValue());
            $el["phoneNumber2"] = trim($sheet->getCell($phoneNumber2.$i)->getValue());
            $el["phoneNumber3"] = trim($sheet->getCell($phoneNumber3.$i)->getValue());
            //phoneNumber2
            $el["emailOrganization"] = trim($sheet->getCell($emailOrganization.$i)->getValue());

            array_push($data, $el);
        }
        return $data;
        
    }

    private function getColumns(Worksheet $sheet, array $matching = [], string $cityName=null, array $firstColumns=[1,2]) {
        //$reader = new Xlsx();
        //$spreadsheet = $reader->load(dirname(__DIR__) ."/".$fileName);


            $countRow = $sheet->getHighestRow();
            $idColumn = $sheet->getHighestColumn();
            $countColumn = $this->convertExcelColumnToNumber($idColumn);
            //var_dump($idColumn);
            
            $colsName = $this->convertToExcelColumnList($countColumn);
            //var_dump($colsName);
            $dataSheet = [];
            
            //$tampon = [];
            //$totalLine=0;
            //$fileNameList = fctRetirerAccents(strtolower($fileName));
            /*var_dump($countRow);
            die();*/
            foreach ($firstColumns as $key => $firstColumn) 
            {
                $el = [];
                $el["row"] = $firstColumn;
                //var_dump($sheet->getTitle());
        
                foreach ($colsName as $key => $colName) 
                {
        
                    $el[$colName.$firstColumn] = trim($sheet->getCell($colName.$firstColumn)->getValue());
        
                }
                //var_dump($el);
                
        
                array_push($dataSheet, $el);   
                
            }
        //die();

        return $dataSheet;
        
    }

    private function getCheets(Spreadsheet $spreadsheet) : array {
        //$reader = new Xlsx();
        //$spreadsheet = $reader->load(dirname(__DIR__) ."/".$fileName);
        $sheets = $spreadsheet->getAllSheets();
        $w = new Worksheet();
        $res = [];
        //$w->setC;
        foreach ($sheets as $key => $sheet) {
            
            array_push($res, [
                "name" => $sheet->getTitle()
            ]);

        }
        return $res;

    }

    private function getSpreadsheet(string $fileName) : Spreadsheet {
        $reader = new Xlsx();
        $spreadsheet = $reader->load(dirname(__DIR__) ."/".$fileName);

        return $spreadsheet;
    }

    private function downloadDataAndSave(string $url) : string 
    {
        $stream = $this->fileUploader->downloadStreamGoogle($url);

        $path = "/tmp/". uniqid("agrodata-").".xlsx";

        file_put_contents($path, $stream->getContents());

        return $path;
        
    }
    private function convertToExcelColumn($number) {
        $columnName = '';
    
        while ($number > 0) {
            $remainder = ($number - 1) % 26;
            $columnName = chr(65 + $remainder) . $columnName;
            $number = intval(($number - $remainder) / 26);
        }
    
        return $columnName;
    }
    private function convertToExcelColumnList(int $number) : array {
        $columns = [];
        for ($i=1; $i <= $number; $i++) { 
            
            $columnName = $this->convertToExcelColumn($i);
            $columns[] = $columnName;
        }
        return $columns;
    }
    private function convertExcelColumnToNumber($column) {
        $column = strtoupper($column); // Convertir en majuscules pour assurer la compatibilité
        $length = strlen($column);
        $number = 0;
    
        for ($i = 0; $i < $length; $i++) {
            $char = ord($column[$i]) - 64; // Convertir la lettre en valeur ASCII et soustraire 64 pour obtenir la valeur numérique de la colonne
            $number = $number * 26 + $char; // Calculer la valeur numérique totale en multipliant la valeur précédente par 26 et en ajoutant la valeur actuelle
        }
    
        return $number;
    }




}