<?php
namespace Signature;

use Signature\Exception\AuthenticationException;

class Server
{
    protected $signer;

    protected $time_span = 600;

    public function __construct(Signer $signer)
    {
        $this->signer = $signer;
    }

    public function authorize($secret, $method, $path, $params)
    {
        if (!isset($params['auth_key'])) {
            throw new AuthenticationException("auth_key is missing");
        }

        if (!isset($params['auth_timestamp']) || $this->validateTimestamp($params['auth_timestamp'])) {
            throw new AuthenticationException("auth_timestamp is missing or invalid");
        }

        if (!isset($params['auth_signature'])) {
            throw new AuthenticationException("auth_signature is missing");
        }

        $auth_signature = $params['auth_signature'];
        unset($params['auth_signature']);

        if ($auth_signature != $this->signer->sign($secret, $method, $path, $params)) {
            throw new AuthenticationException("auth_signature is invalid");
        }

        return true;
    }

    protected function validateTimestamp($timestamp)
    {
        return abs((time() - $timestamp)) > $this->time_span;    
    }

}