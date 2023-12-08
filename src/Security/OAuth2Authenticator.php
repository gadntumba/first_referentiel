<?php

namespace App\Security;

use App\Security\User\OAuthUserProvider;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use \Mink67\Security\Converters\AccessTokenToUser;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class OAuth2Authenticator extends AbstractAuthenticator {


    /**
     */
    public function __construct(
        private ContainerBagInterface $containerBag,
        private OAuthUserProvider $oAuthUserProvider
    )
    {
    }

    public function start(Request $request, AuthenticationException $authException = null)
    { 
        $data = [
            // you might translate this message
            'message' => "Not token emit"
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
    /**
     * 
     */
    public function authenticate(Request $request): Passport
    {
        $identifier = "" ;
        $bearerToken = $request->headers->get("Authorization");
        if (!is_null($bearerToken)) {
            
            $identifier = str_replace("Bearer ", "", $request->headers->get("Authorization"));
        }

        //dd($request->headers->get("Authorization"));

        $me = $this;

        return new SelfValidatingPassport(
            new UserBadge(
                $identifier,
                function (string $identifier) use($me)
                {
                    if (empty($identifier)) {
                        return null;
                    }
                    $accessToken = new AccessToken(["access_token" => $identifier, "token_type" => "bearer"]);
                    return $me->convertToken($accessToken);
                }
            )
        );
    }
    /**
     * 
     */
    private function convertToken(AccessToken $accessToken) {
        $jwt = $accessToken->getToken();
        $publicKey= file_get_contents($this->containerBag->get("public_key_file_path"));
        //dd($jwt);
        $decoded = JWT::decode($jwt, new Key($publicKey, 'RS256'));
        return $this->oAuthUserProvider->getOAuthUser(
            $accessToken,
            $decoded->roles,
            $decoded->aud,
            $decoded->phoneNumber
        );
    }

    public function supports(Request $request): ?bool
    {

        $bearerToken = $request->headers->get("Authorization");
        return !is_null($bearerToken);
        //return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            //'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
            'message'=>'Authentification échouée'
            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }

    public function supportsRememberMe() : bool 
    {
        return false;
    }

}