<?php
namespace App\Objects;

class AuthKeys
{
    public const LOGIN_ID = '6dd490faf9cb87a9862245da41170ff2';
    public const SECRET_KEY = '024h1IlD';
    public const URL_PLACETOPAY = 'https://test.placetopay.com/redirection';

    var $seed;
    var $nonce;
    var $tranKey;

    public function __construct()
    {
        $this->seed = '';
        $this->nonce = '';
        $this->tranKey = '';
        $this->setAuthKeys();
    }

    public function setAuthKeys()
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

    public function getSeed()
    {
        return $this->seed;
    }

    public function getNonce()
    {
        return $this->nonce;
    }

    public function getTranKey()
    {
        return $this->tranKey;
    }

    public function getAuth()
    {
        $auth = array(
            'login'        => $this::LOGIN_ID,
            'tranKey'      => $this->tranKey,
            'url'          => $this::URL_PLACETOPAY
        );
        return $auth;
    }
}