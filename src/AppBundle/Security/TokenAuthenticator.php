<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 10/04/2017
 * Time: 19:52
 */

namespace AppBundle\Security;

use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var TokenHelper
     */
    private $tokenHelper;
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * TokenAuthenticator constructor.
     * @param TokenHelper $tokenHelper
     * @param UserManager $userManager
     */
    public function __construct(TokenHelper $tokenHelper, UserManager $userManager)
    {
        $this->tokenHelper = $tokenHelper;
        $this->userManager = $userManager;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getCredentials(Request $request)
    {
        xdebug_break();
        $head = $request->headers->get('Authorization');

        if (empty($head)) {
            return null;
        }
        $token = substr($head, 7);
        return ['token' => $token];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $data = $this->tokenHelper->decodeToken($credentials['token']);

        if (empty($data)) {
            throw new CustomUserMessageAuthenticationException('Invalid Token');
        }
        $username = $data->username;
        return $this->userManager->findUserByUsername($username);;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        xdebug_break();
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        );

        return $data;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function supportsRememberMe()
    {
        return false;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = array(
            // you might translate this message
            'message' => 'Authentication Required'
        );

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}