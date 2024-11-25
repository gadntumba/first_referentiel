<?php
namespace App\Services;

use App\Entity\Instigator;
use App\Entity\Productor;
use App\Repository\InstigatorRepository;
use App\Repository\ProductorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ManagerAgromwindaToken 
{
    public function __construct(
        private EntityManagerInterface $em,
        private HttpClientInterface $httpClient,
        private ProductorRepository $productorRepository,
        private ContainerBagInterface $containerBag,
        private InstigatorRepository $instigatorRepository
    ) 
    {
        
    }

    public function getToken() : ?string 
    {

        $host = $this->containerBag->get("agromwinda_host");
        $pwd = $this->containerBag->get("agromwinda_admin_pwd");
        $username = $this->containerBag->get("agromwinda_admin_username");

        //dump($username);
        //dd($pwd);
        
        $host = "https://api.agromwinda.com";
        $response = $this->httpClient->request(
            "POST",
            $host."/auths",
            [
                "headers" => [
                    "Content-Type" => "application/json",
                ],
                "json"  => [
                    "login" => $username,
                    "password" => $pwd
                ]
            ]
        );

        $statusCode = $response->getStatusCode();
        $isOK = $statusCode >=200 && 300 > $statusCode;
        $arr = $response->toArray(false);
        try {
            $arr = $response->toArray(false);
            #dd($isOK && isset($arr["user"]));

            if ($isOK && isset($arr["user"])) 
            {
                //$investigator = new Instigator();
                #dd($arr["user"]["token"]);
                return $arr["user"]["token"];

            }else 
            {
                return null;

            }
            
        } catch (\Throwable $th) {
            #dd($th);
            return null;
            //throw $th;
        }

        
    }

}