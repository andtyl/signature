<?php
namespace Signature;

class Client
{
    protected $signer;

    public function __construct(Signer $signer)
    {
        $this->signer = $signer;
    }

    public function getSignedRequestParams($key, $secret, $method, $path, $params)
    {
        $params['auth_key'] = $key;
        $params['auth_timestamp'] = time();
        $auth_signature = $this->signer->sign($secret, $method, $path, $params);
        $params['auth_signature'] = $auth_signature;

        return $params;
    }
}