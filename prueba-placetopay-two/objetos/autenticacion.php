<?php
namespace Objetos;

class Autenticacion
{
    const LOGIN_ID = '6dd490faf9cb87a9862245da41170ff2';
    const SECRET_KEY = '024h1IlD';
    const URL_PLACETOPAY = 'https://test.placetopay.com/redirection';

    public $seed;
    public $nonce;
    public $tranKey;

    public function __construct()
    {
        $this->seed = '';
        $this->nonce = '';
        $this->tranKey = '';
        $this->setAutenticacion();
    }

    public function setAutenticacion()
    {
        if (isset($this->seed)) {
            $this->seed = date('c');
        }
        if (function_exists('random_bytes')) {
            $this->nonce = bin2hex(random_bytes(16));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $this->nonce = bin2hex(openssl_random_pseudo_bytes(16));
        } else {
            $this->nonce = mt_rand();
        }
        if (isset($this->tranKey)) {
            $this->tranKey = base64_encode(sha1($this->nonce . $this->seed . $this::SECRET_KEY, true));
        }
    }

    public function getAutenticacion()
    {
        $auth = array(
            'login'        => $this::LOGIN_ID,
            'tranKey'      => $this->tranKey,
            'url'          => $this::URL_PLACETOPAY
        );
        return $auth;
    }
}