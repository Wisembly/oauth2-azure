<?php
namespace Wisembly\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class AzureResourceOwner implements ResourceOwnerInterface
{
    private $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId()
    {
        return isset($this->response['id']) ? $this->response['id'] : null;
    }

    public function toArray()
    {
        return $this->response;
    }
}