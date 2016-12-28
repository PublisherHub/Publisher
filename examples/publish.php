<?php

use Publisher\Manager\Publisher;
use Publisher\Supervision\PublisherSupervisor;
use Publisher\Helper\EntryHelper;
use Publisher\Entry\Factory\EntryFactory;
use Publisher\Monitoring\Monitor;


# CHECK configuration #

require_once __DIR__.'/config.example.php'; // import $publisherConfig

$supervisor = new PublisherSupervisor($publisherConfig);

// you can check if all configured Entries and Modes are available 
$notFoundClasses = $supervisor->checkConfig();
var_dump($notFoundClasses);


# SETUP Publisher #

$entryHelper = new EntryHelper($supervisor);
$entryFactory = new EntryFactory($entryHelper);
/* right now the monitor uses a symfony session instance,
 * this specific monitor will get its own package soon.
 */
$session = new \Symfony\Component\HttpFoundation\Session\Session();
$monitor = Monitor::getInstance($session);

$publisher = new Publisher(
    $entryHelper,
    $entryFactory,
    $requestorFactory, // you need an additional package for this one
    $monitor
);


# Collect entry data #

/*
 * Now we offer the user forms that represents posts.
 * For example the user can choose a title, a message and an url
 * foreach entry with the mode Recommendation.
 * We get following input.
 */
$recommendationData = array(
    0 => array(
        'entry' => 'FacebookPage',
        'content' => array(
            'title' => 'test',
            'message' => 'foo and bar',
            'url' => 'http://www.example.com'
        ),
        'parameters' => array(
            'pageId' => '1234567890',
            'pageAccessToken' => 'wsad1234qwer5678xy90'
        )
    ),
    1 => array(
        'entry' => 'TwitterPage',
        'content' => array(
            'title' => 'test',
            'message' => 'foo',
            'url' => 'http://www.example.com'
        )
    )
);

# PREPARE entry data #

/*
 * To be able to send this data to the specific services we need to transform the data
 * specific to the service and entry type.
 * For example Facebook page entries allow the message to be in the body parameter 'message'
 * while Twitter user entries use 'status' instead.
 */
$contentTransformer = new \Publisher\Mode\ContentTransformer($supervisor);
$entryData = $contentTransformer->transform('Recommendation', $recommendationData);

/* 
 * We should have retrieved something like the following data.
 */
$entryData = array(
    0 => array(
        'entry' => 'FacebookPage',
        'content' => array(
            'message' => "test\nfoo and bar",
            'link' => 'http://www.example.com'
        ),
        'parameters' => array( // were ignored by the content transformer
            'pageId' => '1234567890',
            'pageAccessToken' => 'wsad1234qwer5678xy90'
        )
    ),
    1 => array(
        'entry' => 'TwitterUser',
        'content' => array(
            'status' => "test\nfoo\nhttp://www.example.com"
        )
    )
);


# PUBLISH entries #

$publisher->setupEntries($entryData);

/* Executes the requests to post each Entry.
 * In the process it will follow the order given by $entryData.
 * In this case it'll publish the 'FacebookPage' Entry first
 * and after that the 'TwitterUser' Entry.
 */
$publisher->publishAll();


# REVIEW publishment #

/* Now lets see if every Entry was posted successfully.
 * Lists the entry id with their status:
 * true => succeeded
 * false => failed
 */
$status = $publisher->getStatus();

var_dump($status);


# CLEAN UP #

/* Posts with the Entries (more exact their entry ids) are currently blocked.
 * Now no Entry can be posted more than once accidently.
 * To allow new posts with the same entry id,
 * you need to clear their status.
 */
$publisher->clearStatus();

/*
 * You should clear the current entry data from session, too.
 */