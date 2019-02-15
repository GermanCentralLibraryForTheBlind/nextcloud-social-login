<?php
/*!
* Hybridauth
* https://hybridauth.github.io | https://github.com/hybridauth/hybridauth
*  (c) 2019 Hybridauth authors | https://hybridauth.github.io/license.html
*/

namespace OCA\SocialLogin\Provider;

use Hybridauth\Adapter\OAuth2;
use Hybridauth\Exception\UnexpectedApiResponseException;
use Hybridauth\Data;
use Hybridauth\User;

/**
 * DZB OAuth2 provider adapter.
 */
class DZB extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    public $scope = 'profile';

    /**
     * {@inheritdoc}
     */
    protected $apiBaseUrl = 'http://localhost/auth/realms/dzb/';

    /**
     * {@inheritdoc}
     */
    protected $authorizeUrl = 'http://localhost/auth/realms/dzb/protocol/openid-connect/auth';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenUrl = 'http://localhost/auth/realms/dzb/protocol/openid-connect/token';

    /**
     * {@inheritdoc}
     */
    protected $apiDocumentation = 'https://www.keycloak.org/docs-api/4.8/rest-api/index.html';

    /**
     * {@inheritdoc}
     */
    public function getUserProfile()
    {
        $response = $this->apiRequest('protocol/openid-connect/userinfo');

        $data = new Data\Collection($response);

        if (!$data->exists('preferred_username')) {

            throw new UnexpectedApiResponseException('Provider API returned an unexpected response.');
        }

        $userProfile = new User\Profile();

        $userProfile->identifier  = $data->get('preferred_username');
        $userProfile->displayName = $data->get('name');
        $userProfile->email       = $data->get('email');

        return $userProfile;
    }
}
