<?php
namespace Wisembly\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Psr\Http\Message\ResponseInterface;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;

class Azure extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public function getBaseAuthorizationUrl()
    {
        return 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize';
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://login.microsoftonline.com/common/oauth2/v2.0/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return 'https://graph.microsoft.com/v1.0/me';
    }

    protected function getDefaultScopes()
    {
        return [
            'user.read',
        ];
    }

    protected function getScopeSeparator()
    {
        return ' ';
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new AzureResourceOwner($response);
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (isset($data['odata.error']) || isset($data['error'])) {
            if (isset($data['odata.error']['message']['value'])) {
                $message = $data['odata.error']['message']['value'];
            } elseif (isset($data['error']['message'])) {
                $message = $data['error']['message'];
            } else {
                $message = $response->getReasonPhrase();
            }

            throw new IdentityProviderException(
                $message,
                $response->getStatusCode(),
                $response
            );
        }
    }
}