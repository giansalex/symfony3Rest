<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 10/04/2017
 * Time: 15:51
 */

namespace AppBundle\Security;

use \Firebase\JWT\JWT;

class TokenHelper
{
    private $key = "y1Gm_anG";

    /**
     * Obtiene un token apartir de data.
     *
     * @param array $data
     * @return string
     */
    public function getToken($data)
    {
        $jwt = JWT::encode($data, $this->key);
        return $jwt;
    }

    /**
     * Retorna la informacion del JWT.
     * @param string $token
     * @return object
     */
    public function decodeToken($token)
    {
        try {
            $decoded = JWT::decode($token, $this->key, array('HS256'));
            return $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }
}