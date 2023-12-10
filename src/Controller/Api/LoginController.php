<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use \Symfony\Contracts\HttpClient\ResponseInterface;

class LoginController extends AbstractController
{
    #[Route('/api/login', name: 'app_api_login', methods:['POST'])]
    public function index(
        Request $request,
        HttpClientInterface $httpClient
    ): Response
    {
        //dd($request->headers->get("content-type"));
        if ($request->headers->get("content-type") == "application/json") {
            $data = json_decode($request->getContent(), true);
        }else {
            $data = $request->request->all();
        }
        $data["grant_type"] = "password";
        $data["client_id"] = $this->getParameter("oauth_client_id");
        $data["client_secret"] = $this->getParameter("oauth_client_secret");
        $data["scope"] = "";

        $response = $httpClient->request(
            'POST',
            'https://oauth.agromwinda.com/oauth/token',
            [
                "json" => $data,
                "headers" => [
                    "Content-Type" => "application/json",
                    "Accept" => "application/json"
                ]
            ]
        );
        //dd($response->getContent(false));
        $statusCode = $response->getStatusCode();
        //$data = $response->toArray(false);
        
        if ($statusCode >= 200 && 300 > $statusCode) {
            return new JsonResponse(
                $response->toArray(false)
            );
        }

        //dd($statusCode);
        $args = $data;
        $data = $response->toArray(false);
        $data["args"] = [
            "username" => $args["username"],
            "password" => $args["password"],
        ];
        //throw new HttpException(400, "Invalid Credentials");
        return new JsonResponse(
            $data,
            400
        );

    }
}
