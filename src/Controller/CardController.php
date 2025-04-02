<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * 
 * 
 */
class CardController extends AbstractController
{
    #[Route('/print/rna/cards/productor/{code}', name: 'app.rna.card.url.print')]
    public function printRNACard($code, HttpClientInterface $httpClient): Response
    {
        //$this->createMemberId($user);
        $url = "http://producer.surintrants.com/api/productors/".$code;
        $resp = $httpClient->request("GET", $url);
        $statusCode = $resp->getStatusCode();
        

        if ($statusCode >=200 && 300 > $statusCode) {
            $data = $resp->toArray();
            $address = "";
            $line = $data["housekeeping"]["address"]["line"];

            if(isset($data["housekeeping"]["address"]["town"])) {
                $townName = isset($data["housekeeping"]["address"]["town"]["name"]) ? $data["housekeeping"]["address"]["town"]["name"] : "" ;
                $cityName = isset($data["housekeeping"]["address"]["town"]["city"]["name"]) ? $data["housekeeping"]["address"]["town"]["city"]["name"] : "" ;
                $provinceName = isset($data["housekeeping"]["address"]["town"]["city"]["province"]["name"]) ? $data["housekeeping"]["address"]["town"]["city"]["province"]["name"] : "" ;
                $address1 = $cityName . ", " .$townName . ", " . $line;
                $address2 = $provinceName ;
            }elseif (isset($data["housekeeping"]["address"]["sector"])) {
                $sectorName = isset($data["housekeeping"]["address"]["sector"]["name"]) ? $data["housekeeping"]["address"]["sector"]["name"] : "" ;
                $territoryName = isset($data["housekeeping"]["address"]["sector"]["territory"]["name"]) ? $data["housekeeping"]["address"]["sector"]["territory"]["name"] : "" ;
                $provinceName = isset($data["housekeeping"]["address"]["sector"]["territory"]["province"]["name"]) ? $data["housekeeping"]["address"]["sector"]["territory"]["province"]["name"] : "" ;
                $address1 = $territoryName .", " . $sectorName . ", " . $line;
                $address2 = $provinceName ;
            }

            $dateString = isset($data["personnalIdentityData"]["birthdate"])? $data["personnalIdentityData"]["birthdate"] : "";

            $date = new \DateTime($dateString);
            $formattedDate = $date->format("d-m-Y");

            return $this->render('card/index.html.twig', [
                'controller_name' => 'CardController',
                'data' => $data,
                'address1' => $address1,
                'address2' => $address2,
                'formattedDate' => $formattedDate,
            ]);
        }

        throw new HttpException(400, "Not found");

        //dd($data);

        //dd($user->getNfcId());
        
        if (is_null($user->getBiodata())) {
            //$this->addFlash("notice", "L'utilisatuer n'a pas de Biodata");
            //return $this->redirectToRoute("subscriber.show", ["slug" => $user->getSlug()]);
        }
        //$user = $userRepository->findOneBy(["slug" => $slug]);
        //dd($user->getNfcId());//nfcId
        
    }
    #[Route('/plateform/card/users/{username}', name: 'app.platform.card')]
    public function index(User $user, UserRepository $userRepository ): Response
    {
        $this->createMemberId($user);

        //dd($user->getNfcId());
        
        if (is_null($user->getBiodata())) {
            //$this->addFlash("notice", "L'utilisatuer n'a pas de Biodata");
            //return $this->redirectToRoute("subscriber.show", ["slug" => $user->getSlug()]);
        }
        //$user = $userRepository->findOneBy(["slug" => $slug]);
        //dd($user->getNfcId());//nfcId
        return $this->render('platform/card/card/index.html.twig', [
            'controller_name' => 'CardController',
            'user' => $user,
        ]);
    }


    function createMemberId(User $user) : User {
        $countryId = "234";
        
        if ($user?->getAddress()?->getTown()) {
            $territoryId = $user?->getAddress()?->getTown()?->getId();
        }elseif ($user?->getAddress()?->getGroupment()?->getTerritory()) {
            $territoryId = $user?->getAddress()?->getGroupment()?->getTerritory()?->getId() + 500;
        }else {
            $territoryId = "000";
            //throw new HttpException(400, "L'utilisateur n'a pas d'address");
        }

        $territoryId = str_pad($territoryId, 3, "0", STR_PAD_LEFT);
        $id = str_pad($user?->getId(), 6, "0", STR_PAD_LEFT );
        $id = substr($id, 0, 3) ." ".substr($id, 3, 3);

        $user->setNfcId($countryId ." ".$territoryId . " ". $id);

        return $user;
    }
}