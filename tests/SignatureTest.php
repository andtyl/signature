<?php
use Signature\Client;
use Signature\Server;
use Signature\Signer;
use Signature\Exception\AuthenticationException;

class SignatureTest extends PHPUnit_Framework_TestCase
{
    public function testClient()
    {
        $client = new Client(new Signer());
        $params = $client->getSignedRequestParams("123", "456", "POST", "/hello", array("name" => "Foo"));
        $this->assertArrayHasKey("auth_key", $params);
        $this->assertArrayHasKey("auth_timestamp", $params);
        $this->assertArrayHasKey("auth_signature", $params);
    }

    public function testServer()
    {
        $client = new Client(new Signer());
        $server = new Server(new Signer());
        $result = $server->authenticate("456", "POST", "/hello", $client->getSignedRequestParams("123", "456", "POST", "/hello", array("name" => "Foo")));
        $this->assertTrue($result);
    }

    public function testServerAuthenticationInvalidSecret()
    {
        $this->setExpectedException('Signature\Exception\AuthenticationException');
        $client = new Client(new Signer());
        $server = new Server(new Signer());
        $result = $server->authenticate("000", "POST", "/hello", $client->getSignedRequestParams("123", "456", "POST", "/hello", array("name" => "Foo")));
    }

    //TODO: More...
}