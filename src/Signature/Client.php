<?php
namespace Signature;

class Client
{
    protected $signer;

    public function __construct(Signer $signer)
    {
        $this->signer = $signer;
    }

    /**
     * Get signed parameters
     *
     * @param string $key Key
     * @param string $secret Secret
     * @param string $method HTTP Method
     * @param string $path URL path
     * @param array $params Params
     * @return array Params
     */
    public function getSignedRequestParams($key, $secret, $method, $path, $params)
    {
        $params['auth_key'] = $key;
        $params['auth_timestamp'] = time();
        $auth_signature = $this->signer->sign($secret, $method, $path, $params);
        $params['auth_signature'] = $auth_signature;

        return $params;
    }
}