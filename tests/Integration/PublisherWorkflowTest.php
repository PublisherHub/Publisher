<?php

namespace Integration;

use PHPUnit\Framework\TestCase;
use Publisher\Helper\EntryHelper;
use Publisher\Supervision\PublisherSupervisor;
use Publisher\Requestor\RequestorFactoryInterface;
use Publisher\Requestor\RequestorInterface;
use Publisher\Requestor\Request;
use Publisher\Manager\Publisher;
use Publisher\Monitoring\Monitor;
use Publisher\Entry\Factory\EntryFactory;

/**
 * The Publisher test is designed to imitate
 * and test the workflow of sending Entrys.
 * 
 * The following tests will work with the implemented standard components.
 * It ony uses mocks for components that'll be implemented outside this package.
 */
class PublisherWorkflowTest extends TestCase
{
    
    public function testPublishingWorkflow()
    {
        /* The Entry data is already transformed to be usable for the Publisher.
         * 
         */
        $entryData = [
            0 => [
                'entry' => 'ProviderPage',
                'content' => [
                    'message' => "test\nfoo and bar",
                    'link' => 'http://www.example.com'
                ],
                'parameters' => [
                    'pageId' => 'somePageId',
                    'pageAccessToken' => 'wsad1234qwer5678xy90'
                ]
            ],
            1 => [
                'entry' => 'ServiceUser',
                'content' => [
                    'message' => "test\nfoo\nhttp://www.example.com"
                ]
            ],
            2 => [
                'entry' => 'ServiceForum',
                'content' => [
                    // irrelevant for the ServiceForumEntry as a Mock
                ],
                'parameters' => [
                    'forumId' => 'someForumId'
                ]
            ]
        ];
        
        $publisher = $this->getPublisherManager();
        
        /**
         * 
         */
        $publisher->setupEntries($entryData);
        
        $publisher->publishAll();
        
        $status = $publisher->getStatus();
        
        $this->assertEquals([
            'ServiceUser' => false,
            'ServiceForum' => false,
            'ProviderPage' => true
        ], $status);
        
        $publisher->clearStatus();
    }
    
    /**
     * @return Publisher
     */
    protected function getPublisherManager()
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
        $entryFactory = new EntryFactory($entryHelper);
        $monitor = new Monitor();
        
        $requestorFactory = $this->getRequestorFactoryMock();
        
        return new Publisher($entryHelper, $entryFactory, $requestorFactory, $monitor);
    }
    
    /**
     * @return RequestorFactoryInterface
     */
    protected function getRequestorFactoryMock()
    {
        $requestor = $this->getMockBuilder(RequestorInterface::class)->getMock();
        $requestor->expects($this->any())->method('doRequest')->willReturnCallback([$this, 'doRequest']);
        
        $requestorFactory = $this->getMockBuilder(RequestorFactoryInterface::class)->getMock();
        $requestorFactory->expects($this->any())->method('createByEntry')->willReturn($requestor);
        
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
            case '/somePageId/feed': // Request path for ProviderPage
                $response = new \stdClass();
                $response->id = 'foo';
                return json_encode($response);
                break;
            case '/me/feed': // Request path for ServiceUser
                return json_encode(['success' => false]);
                break;
            case '/forum/someForumId': // Request path for ServiceForum
                return false;
                break;
            default:
                return null;
        }
    }
}
