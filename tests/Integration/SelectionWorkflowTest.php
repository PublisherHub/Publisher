<?php

namespace Integration;

use PHPUnit\Framework\TestCase;
use Publisher\Selector\Manager\SelectorManager;
use Publisher\Selector\Factory\SelectorFactory;
use Publisher\Helper\EntryHelper;
use Publisher\Supervision\PublisherSupervisor;
use Publisher\Requestor\RequestorFactoryInterface;
use Publisher\Requestor\RequestorInterface;
use Publisher\Requestor\Request;
use Publisher\Selector\Factory\SelectionCollectionArrayTransformer;

/**
 * The Selection test is designed to imitate
 * and test the workflow of retrieving Entry parameters.
 * 
 * The following tests will work with the implemented standard components.
 * It ony uses mocks for components that'll be implemented outside this package.
 */
class SelectionWorkflowTest extends TestCase
{
    
    /**
     * @var array imitates the session storage
     */
    protected $session = [];
    
    
    public function testSelectionWorkflow()
    {
        // setup for each request
        $entryIds = ['ProviderPage', 'ServiceUser'];
        
        $selectorManager = $this->getSelectorManager();
        
        
        /* 1. initial request
         * 
         * The user didn't see any kind of form yet
         * to choose parameters.
         */
        $selectorManager->setupSelectors($entryIds);
        
        if (!$selectorManager->areAllParametersSet()) {
            
            $selectorManager->executeCurrentSteps();
            
            $selectionCollections = $selectorManager->getCollectionsAsArray();
            
            foreach ($entryIds as $entryId) {
                 $this->assertArrayHasKey($entryId, $selectionCollections);
            }
            
            /* You should save the SelectionCollections data in e.g. a session
             * to retrieve it for the following requests.
             */
            $this->session['selections'] = $selectionCollections;
            
            /* Use the SelectionCollections (or the data array) to generate a form
             * that the user can use to set or confirm parameters.
             */
        }
        
        
        /* 2. request with first input
         * 
         * The user interacts with the form.
         * Now we'll update the selectors based on the given input.
         */
        $parameters = [
            'ProviderPage' => ['pageId' => 'bar1'],
            'ServiceUser' => []
        ];
        
        $selectorManager->setupSelectors($entryIds, $this->session['selections']);
        
        $selectorManager->updateSelectors($parameters);
        
        if (!$selectorManager->areAllParametersSet()) {
            $selectorManager->executeCurrentSteps();
            
            $this->session['selections'] = $selectorManager->getCollectionsAsArray();
        }
        
        
        /* 3. request
         * 
         * The user sends further parameters.
         */
        $parameters = [
            'ProviderPage' => ['pageId' => 'bar1', 'pageAccessToken' => 'abc123'],
            'ServiceUser' => []
        ];
        
        $selectorManager->setupSelectors($entryIds, $this->session['selections']);
        
        $selectorManager->updateSelectors($parameters);
        
        if (!$selectorManager->areAllParametersSet()) {
            // we shouldn't enter this block anymore
            $this->assertTrue(false);
        } else {
            $parameters = $selectorManager->getParameters();
            $this->assertEquals([
                'ProviderPage' => ['pageId' => 'bar1', 'pageAccessToken' => 'abc123'],
                'ServiceUser' => []
            ], $parameters);
        }
    }
    
    
    /**
     * @return SelectorManager
     */
    protected function getSelectorManager()
    {
        $config = [
            'entries' => [
                'Service' => ['User', 'Forum', 'Page'],
                'Provider' => ['Page']
            ],
            'modes' => []
        ];
        $baseHelper = new PublisherSupervisor($config);
        $entryHelper = new EntryHelper($baseHelper);
        
        $requestorFactory = $this->getRequestorFactoryMock();
        $selectorFactory = new SelectorFactory($entryHelper, $requestorFactory);
        $collectionTransformer = new SelectionCollectionArrayTransformer();
        
        return new SelectorManager($selectorFactory, $collectionTransformer);
    }
    
    /**
     * @return RequestorFactoryInterface
     */
    protected function getRequestorFactoryMock()
    {
        $requestor = $this->getMockBuilder(RequestorInterface::class)->getMock();
        $requestor->expects($this->any())->method('doRequest')->willReturnCallback([$this, 'doRequest']);
        
        $requestorFactory = $this->getMockBuilder(RequestorFactoryInterface::class)->getMock();
        $requestorFactory->expects($this->any())->method('create')->willReturn($requestor);
        
        return $requestorFactory;
    }
    
    /**
     * Mimics the execution of the request by returning responses
     * based on the requests path for the test.
     * 
     * @param Request $request
     */
    public function doRequest(Request $request)
    {
        switch ($request->getPath()) {
            case '/me/accounts': // first Request path for ProviderPage
                $response = new \stdClass();
                $item = new \stdClass();
                $item->name = 'Foo';
                $item->id = 'bar1';
                $response->data = [$item];
                return json_encode($response);
                
            case '/bar1?fields=access_token': // second Request path for ProviderPage
                $response = new \stdClass();
                $response->access_token = 'abc123';
                return json_encode($response);
                
            default:
                return null;
        }
    }
}
