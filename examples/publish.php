<?php

use Publisher\Manager\Publisher;
use Publisher\Supervision\PublisherSupervisor;
use Publisher\Helper\EntryHelper;
use Publisher\Entry\Factory\EntryFactory;
use Publisher\Monitoring\Monitor;

require_once __DIR__.'/config.example.php'; // import $publisherConfig

$supervisor = new PublisherSupervisor($publisherConfig);

// you can check if all configured Entries and Modes are available 
$notFoundClasses = $supervisor->checkConfig();
var_dump($notFoundClasses);

$entryHelper = new EntryHelper($supervisor);
$entryFactory = new EntryFactory($entryHelper);
$session = new \Symfony\Component\HttpFoundation\Session\Session();
$monitor = Monitor::getInstance($session);

$publisher = new Publisher(
        $entryHelper,
        $entryFactory,
        $requestorFactory, // you need an additional package for this one
        $monitor
);

// imagine that we retrived this data from the validated users input
$entryData = array(
    0 => array(
        'entry' => 'FacebookPage', // entry id
        'content' => array('message' => 'foo'), // specific content based on the mode
        'mode' => 'Recommendation' // mode id
    ),
    1 => array(
        'entry' => 'TwitterUser',
        'content' => array('message' => 'foo' /* , ... */),
        'mode' => 'Recommendation'
    )/*,
    * ...
    */
);

$publisher->setupEntries($entryData);

/* Executes the requests to post each Entry.
 * In the process it will follow the order given by $entryData.
 * In this case it'll publish the 'FacebookPage' Entry first
 * and after that the 'TwitterUser' Entry.
 */
$publisher->publishAll();

/* Now lets see if every Entry was posted successfully.
 * Lists the entry id with their status:
 * true => succeeded
 * false => failed
 */
$status = $publisher->getStatus();

var_dump($status);

/* Posts with the Entries (more exact their entry ids) are currently blocked.
 * Now no Entry can be posted more than once accidently.
 * To allow new posts with the same entry id,
 * you need to clear their status.
 */
$publisher->clearStatus();

/*
 * You should clear the current entry data, too.
 */