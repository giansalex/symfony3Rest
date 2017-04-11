<?php

namespace AppBundle\Controller;

use AppBundle\Security\TokenHelper;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class SecurityController.
 *
 * @Rest\Route("/api")
 */
class SecurityController extends FOSRestController
{
    /**
     *  User Login.
     *
     * @Rest\Post("/register")
     *
     * @param Request $request
     * @return bool
     */
    public function postRegisterAction(Request $request)
    {
        $data = json_decode($request->getContent());
        $userManager = $this->get('fos_user.user_manager');
        $newUser = $userManager->createUser();
        $newUser->setUsername($data->username)
                ->setPlainPassword($data->password)
                ->setEmail($data->email)
                ->setEnabled(true);
        $userManager->updateUser($newUser);

        return "Usuario Creado";
    }

    /**
     * Inicia sesion de usuario.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Inicia sesion de usuario",
     *  parameters={
     *    {"name"="user", "dataType"="object", "required"=true, "description"="Usuario y contraseña"}
     *  }
     * )
     *
     * @Rest\Post("/login")
     *
     * @param Request $request
     * @return array|View
     */
    public function postLoginAction(Request $request)
    {
        $credit = json_decode($request->getContent());
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByEmail($credit->email);

        if ($user) {
            $isValid = $this->get('security.password_encoder')
                ->isPasswordValid($user, $credit->password);

            if ($isValid) {
                $helper = $this->get('app.token_helper');
                $jwt = $helper->getToken([
                   'username' => $user->getUsername(),
                    'iss' => $request->getHost(),
                    "iat" => 1356999524,
                    "nbf" => 1357000000
                ]);
                // logea al usuario por cookie.
//                $loginManager = $this->get('fos_user.security.login_manager');
//                $loginManager->logInUser('main', $user);
                return ['token' => $jwt];
            }
        }

        return new View('Invalid credentials', Response::HTTP_BAD_REQUEST);
    }
}
