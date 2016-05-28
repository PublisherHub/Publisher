<?php
/* Couldn't be tested yet, because Google API doesn't allow writing API calls.*/
namespace Publisher\OAuth2;

use OAuth\OAuth2\Service\Google as BaseGoogleService;

use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Http\Client\ClientInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Http\Uri\UriInterface;

use OAuth\Common\Http\Uri\Uri;

class Google extends BaseGoogleService
{
    
    /**
     * {@inheritdoc} 
     */
    public function __construct(
            CredentialsInterface $credentials,
            ClientInterface $httpClient,
            TokenStorageInterface $storage,
            array $scopes = array(),
            UriInterface $baseApiUri = null,
            $apiVersion = ""
    ) {
        parent::__construct(
                $credentials,
                $httpClient,
                $storage,
                array_merge($scopes, $this->getPublisherScopes()),
                $baseApiUri,
                $apiVersion
        );
        $this->baseApiUri = new Uri('https://www.googleapis.com/plusDomains/v1/');
    }
    
    /**
     * {@inheritdoc} 
     */
    public function getPublisherScopes()
    {
        return array(
            Google::SCOPE_GPLUS_ME,
            Google::SCOPE_GPLUS_STREAM_WRITE,
            Google::SCOPE_GPLUS_STREAM_READ
        );
    }
    
    /**
     * {@inheritdoc} 
     */
    public function publish(array $body, array $extraHeaders = array())
    {
        return $this->request('people/me/activities', 'POST', $body, $extraHeaders);  
    }
}