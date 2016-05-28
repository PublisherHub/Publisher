<?php

namespace Publisher\OAuth2;

use OAuth\OAuth2\Service\Facebook as BaseFacebookService;
use Publisher\Validator;

use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Http\Client\ClientInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Http\Uri\UriInterface;

class Facebook extends BaseFacebookService
{
    const MAX_LENGTH_OF_MESSAGE = 63205;
    
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
    }
    
    /**
     * {@inheritdoc} 
     */
    public function getPublisherScopes()
    {
        return array(
            Facebook::SCOPE_PUBLISH_ACTIONS, // user status
            Facebook::SCOPE_PAGES, // -> "manage_pages"
            Facebook::SCOPE_PUBLISH_PAGES
        );
    }
    
    /**
     * {@inheritdoc}
     * 
     * @link https://developers.facebook.com/docs/graph-api/reference/v2.6/user/feed
     */
    public function publish(array $body, array $extraHeaders = array())
    {
        $this->validateBody($body);
        return parent::request('/me/feed', 'POST', $body, $extraHeaders);
    }
    
    /**
     * {@inheritdoc}
     */
    public function validateBody(array $body, $flag = 0)
    {
        switch ($flag) {
            case 0:
                $this->validateBodyForProfile($body);
                break;
            case 1:
                $this->validateBodyForPage($body);
                break;
        }
    }
    
    public function getPages()
    {
        $result = $this->request('/me/accounts', 'GET');
        return $this->parseNameAndId($result);
    }
    
    /**
     * @link https://developers.facebook.com/docs/pages/publishing
     * 
     * @param type $pageId
     * @param array $body
     * @return string
     */
    public function publishAsPage($pageId, array $body)
    {
        $this->validateBody($body, 1);
        $access_token = $this->getPageAccessToken($pageId);
        return $this->request(
                '/'.$pageId.'/feed',
                'POST',
                $body,
                array('Authorization' => 'OAuth ' . $access_token->access_token)
        );
    }
    
    /**
     * @link https://developers.facebook.com/docs/graph-api/reference/v2.6/user/feed
     * 
     * Even so the documentation says otherwise, it is possible to tag someone without giving a place.
     */
    protected function validateBodyForProfile(array $body)
    {
        Validator::checkAnyRequiredParameter($body, array('message', 'link', 'place'));
        if (isset($body['message'])) {
            Validator::validateMessageLength($body['message'], self::MAX_LENGTH_OF_MESSAGE);
        }
    }
    
    /**
     * @link https://developers.facebook.com/docs/pages/publishing
     * 
     * Even so the documentation says otherwise, it is possible to tag someone without giving a place.
     */
    protected function validateBodyForPage(array $body)
    {
        Validator::checkAnyRequiredParameter($body, array('message', 'link', 'place'));
        if (isset($body['message'])) {
            Validator::validateMessageLength($body['message'], self::MAX_LENGTH_OF_MESSAGE);
        }
    }
    
    protected function parseNameAndId($result)
    {
        $association = array();
        $result = json_decode($result);
        foreach ($result->data as $item) {
            $association[$item->name] = $item->id;
        }
        return $association;
    }
    
    protected function getPageAccessToken($pageId)
    {
        $result = $this->request('/'.$pageId.'?fields=access_token', 'GET');
        return json_decode($result);
    }
}