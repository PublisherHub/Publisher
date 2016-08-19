# Publisher
Publish / post via OAuth1 and OAuth2 services.

Publisher offers classes and interfaces that allow you to post several entries.

# Main components
- Entry
    - EntryInterface and AbstractEntry as an abstraction of entries in a social network
- Mode
    - a strategy of filling an Entry with content
- Selector
    - helps you to collect all parameters that the entry requires (e.g. a forum id if you want to post in a forum)
- Publisher
    - manages postings of multiple entries at once

# Installation
The recommended way to install this is through [composer](http://getcomposer.org).

Edit your `composer.json` and add:

```json
{
    "require": {
        "publisher/publisher": "dev-master"
    }
}
```

And install dependencies:

```bash
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar install
```

# Suggestions
- Entry
    - publisher/facebook-entry -> adds entries for posting on Facebook
    - publisher/twitter-entry -> adds entries for posting on Twitter
    - publisher/xing-entry -> adds entries for posting on XING
- Mode
    - publisher/recommendation -> adds mode for publishing
- Requestor
    - phpoauthlib/requestor -> an implementation of a RequestorFactory that uses lusitanian/oauth
- other
    - publisher/form-bundle -> offers forms, views and translations for the publishing process
    - publisher/publisher-symfony-bundle -> add publisher to your Symfony app
    - publisher/publisher-silex-bundle -> add publisher to your Silex app

# Examples
Examples of basic usage are located in the examples/ directory.


# Development

## Package structure (namespaces)
Szenario:
A Service called 'Foo' offers to post a status and to post in groups that the user is a member of.
The Entries of this package implement the two Modes 'Recommendation' and 'SimpleMessage'.

FooEntry:
- Entity
    - Mode
        - Recommendation
            - FooUserRecommendation.php
            - FooGroupRecommendation.php
        - SimpleMessage
            - FooUserSimpleMessage.php
            - FooGroupSimpleMessage.php
- Selector
    - FooGroupSelector.php
- FooUserEntry.php
- FooGroupEntry.php
            

Recommendation:
- Entity
    - AbstractRecommendation.php
- Form
    - Type
        RecommendationType.php
- Resources
    - translations
    - views
- RecommendationInterface.php
- RecommendationMode.php

Modes should provide a Form and an Entity for validation.
Entries that implement a Mode should implement the abstract entity given by the Mode.


## Conventions

- EntyNamespace:   Publisher\Entry\ServiceId
- EntryClassNames: ServiceId(User|Page|Group|Forum)Entry

- SelectorNamespace:  Publisher\Entry\ServiceId\Selector
- SelectorClassNames: ServiceId(User|Page|Group|Forum)Selector

The Selector should be matching with the id of the Entry that it belongs to.
If their is no Selector needed, then their is no need to implement one.
But you should add the 'Selector' directory nonetheless.


- ModeNamespace:  Publisher\Mode\ModeId
- InterfaceClass: ModeIdInterface
- ModeClass:      ModeIdMode


It is recommended to follow these conventions.
In this way you can rely on the already implemented
Supervisor to find the Entries and Modes,
when the EntryHelper requires them.