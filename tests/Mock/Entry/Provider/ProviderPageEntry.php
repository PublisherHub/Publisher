<?php

namespace Publisher\Entry\Provider;

use Publisher\Entry\AbstractEntry;
use Publisher\Helper\Validator;

class ProviderPageEntry extends AbstractEntry
{
    
    const MAX_LENGTH_OF_MESSAGE = 63205; 
    
    /**
     * @inheritDoc
     */
    public static function getPublisherScopes()
    {
        return array('manage_pages', 'publish_pages');
    }
    
    /**
     * @inheritDoc
     */
    protected function defineRequestProperties()
    {
        $this->request->setPath('/?/feed');
        $this->request->setMethod('POST');
    }
    
    /**
     * @inheritDoc
     */
    protected function setParameters(array $parameters)
    {
        Validator::checkRequiredParametersAreSet(
                $parameters,
                array('pageId', 'pageAccessToken')
        );
        $this->addPageIdToPath($parameters['pageId']);
        $this->addPageAccessTokenToHeaders($parameters['pageAccessToken']);
    }
    
    protected function addPageIdToPath($pageId)
    {
        $incompletePath = $this->request->getPath();
        $path = preg_replace('/(\?)/', $pageId, $incompletePath);
        $this->request->setPath($path);
    }
    
    protected function addPageAccessTokenToHeaders(string $pageAccessToken)
    {
        $this->request->addHeaders(array(
            'Authorization' => 'OAuth '.$pageAccessToken
        ));
    }
    
    /**
     * @inheritDoc
     */
    protected function validateBody(array $body)
    {
        Validator::checkAnyRequiredParameter($body, array('message', 'link', 'place'));
        if (isset($body['message'])) {
            Validator::validateMessageLength($body['message'], self::MAX_LENGTH_OF_MESSAGE);
        }
    }
    
    // Implementation of MonitoredInterface
    
    /**
     * @inheritDoc
     */
    public static function succeeded($response)
    {
        $object = json_decode($response);
        return (isset($object->id));
    }
    
}