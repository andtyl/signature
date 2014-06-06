<?php
namespace Signature;

use Signature\Exception\AuthenticationException;

class Server
{
    /**
     * @var Signer Instance of Signer
     */
    protected $signer;

    /**
     * @var int Seconds allowed difference between request timestamp and server time
     */
    protected $time_span = 600;


    /**
     * Constructor
     *
     * @param Signer $signer Signer Instance
     */
    public function __construct(Signer $signer)
    {
        $this->signer = $signer;
    }

    /**
     * Authorize a request (signature parameter)
     *
     * @param string $secret Secret
     * @param string $method HTTP Method
     * @param string $path URL Path
     * @param array $params Params
     * @return array Params
     */
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

    /**
     * Validate timestamp
     *
     * @param int $timestamp Unix Timestamp
     * @return bool Is OK
     */
    protected function validateTimestamp($timestamp)
    {
        return abs((time() - $timestamp)) > $this->time_span;    
    }
}