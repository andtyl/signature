<?php
namespace Signature;

class Signer
{
    /**
     * Create a signature
     *
     * @param string $secret Secret
     * @param string $method HTTP Method
     * @param string $path URL path
     * @param array $params Params
     * @return string Signature
     */
    public function sign($secret, $method, $path, $params)
    {
        $method = strtoupper($method);
        $path = '/' . ltrim($path, '/');

        $params = array_change_key_case($params, CASE_LOWER); //Lower case
        ksort($params, SORT_STRING); //Sorted
        $parameter_string = http_build_query($params);

        $string_to_sign = implode("\n", array(
            $method,
            $path,
            $parameter_string
        ));

        return hash_hmac('sha256', $secret, $string_to_sign);
    }
}