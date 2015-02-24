# FeedIoBundle

[![Latest Stable Version](https://poser.pugx.org/debril/feedio-bundle/v/stable.svg)](https://packagist.org/packages/debril/feedio-bundle)
[![Build Status](https://secure.travis-ci.org/alexdebril/rss-atom-bundle.png?branch=master)](http://travis-ci.org/alexdebril/rss-atom-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexdebril/rss-atom-bundle/badges/quality-score.png?s=6e4cc3b9368ddbf14b1066114b6af6d9011894d9)](https://scrutinizer-ci.com/g/alexdebril/rss-atom-bundle/)
[![Code Coverage](https://scrutinizer-ci.com/g/alexdebril/rss-atom-bundle/badges/coverage.png?s=5bbd191f3b9364b8c31d8f1881f4c1fd06829fc3)](https://scrutinizer-ci.com/g/alexdebril/rss-atom-bundle/)

feed-io bundle for the Symfony Framework

# What is feed-io ?

[feed-io](https://github.com/alexdebril/feed-io) is a PHP library built to consume and serve RSS / Atom feeds. It features:

- Atom / RSS read and write support
- HTTP Headers support when reading feeds in order to save network traffic
- Detection of the format (RSS / Atom) when reading feeds
- PSR compliant logging
- Content filtering to fetch only the newest items
- DateTime detection and conversion
- A generic HTTP ClientInterface
- Guzzle Client integration

## FeedIoBundle features

- A generic StreamController built to write all your feeds. This controller is able to send a 304 HTTP Code if the feed didn't change since the last visit
- A generic StorageInterface to handle read/write operations on a data source
- Commands to save external feeds' content

Keep informed about new releases and incoming features : http://blog.debril.fr/category/feed-io

# Installation

Edit composer.json and add the following line in the "require" section:

    "debril/feedio-bundle": "dev-master"

then, ask Composer to install it:

    composer.phar update debril/feedio-bundle
    
finally, edit your app/AppKernel.php to register the bundle in the registerBundles() method as above:


```php
class AppKernel extends Kernel
{

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            // ...
            // register the bundle here
            new Debril\FeedIoBundle\DebrilFeedIoBundle(),
```

# Fetching the repository

Do this if you want to contribute (and you're welcome to do so):

    git clone https://github.com/alexdebril/FeedIoBundle.git

    composer.phar install

# Unit Testing

You can run the unit test suites using the following command in the Bundle's source director:

    bin/phpunit

Usage
=====

feedio-bundle is designed to read feeds across the internet and to publish your own. Its main class is [FeedIo](https://github.com/alexdebril/feed-io/blob/master/src/FeedIo/FeedIo.php) :

## reading

```php

// get it through the container
$feedIo = $this->container->get('feedio');

// read a feed
$result = $feedIo->read($url);

// or read a feed since a certain date
$result = $feedIo->readSince($url, new \DateTime('-7 days'));

// get title
$feedTitle = $result->getFeed()->getTitle();

// iterate through items
foreach( $result->getFeed() as $item ) {
    $item->getTitle();
}

```

## formatting an object into a XML stream

```php

// get it through the container
$feedIo = $this->container->get('feedio');

// build the feed
$feed = new \FeedIo\Feed;
$feed->setTitle('...');

// convert it into Atom
$dom = $feedIo->toAtom($feed);

// or ...
$dom = $feedIo->format($feed, 'atom');

```

## Providing feeds using the StreamController
----------------
FeedIoBundle offers the ability to provide RSS/Atom feeds. The route will match the following pattern : /{format}/{id}

- {format} must be "rss" or "atom" (or whatever you want if you add the good routing rule in routing.yml)
- {contentId} is an optional argument. Use it you have several feeds

The request will be handled by `StreamController`, according to the following steps :

- 1 : grab the ModifiedSince header if it exists
- 2 : get the storage defined in services.xml and calls the `getFeed($id)` method
- 4 : compare the feed's LastModified property with the ModifiedSince header
- 5 : if LastModified is prior or equal to ModifiedSince then the response contains only a "NotModified" header and the 304 code. Otherwise, the stream is built and sent to the client

StreamController expects the getFeed()'s return value to be a [\FeedIo\FeedInterface](https://github.com/alexdebril/feed-io/blob/master/src/FeedIo/FeedInterface.php) instance. It can be a [\FeedIo\Feed](https://github.com/alexdebril/feed-io/blob/master/src/FeedIo/Feed.php) or a class you wrote and if so, your class MUST implement \FeedIo\FeedInterface.

# Useful Tips

## Skipping 304 HTTP Code

The HTTP cache handling can be annoying during development process, you can skip it through configuration in your app/config/parameters.yml file :

```yml
parameters:
    force_refresh:     true
```

This way, the `StreamController` will always display your feed's content and return a 200 HTTP code.

# Contributors

* Alex Debril
* Elnur Abdurrakhimov https://github.com/elnur
* matdev https://github.com/matdev
