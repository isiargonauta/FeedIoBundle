<?php

/**
 * FeedIoBundle
 *
 * @package FeedIoBundle/Controller
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2015, Alexandre Debril
 *
 */

namespace Debril\FeedIoBundle\Controller;

use FeedIo\Feed;
use Debril\FeedIoBundle\Exception\FeedNotFoundException;
use Debril\FeedIoBundle\Adapter\StorageInterface;    
use Debril\FeedIoBundle\DependencyInjection\FeedIoContainerTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class StreamController extends Controller
{

    use FeedIoContainerTrait;

    /**
     * parameter used to force refresh at every hit (skips 'If-Modified-Since' usage).
     * set it to true for debug purpose
     */
    const FORCE_PARAM_NAME = 'force_refresh';

    /**
     *
     * @var \DateTime
     */
    protected $since;

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $this->setModifiedSince($request);

        return $this->createStreamResponse(
                        $request->get('id'),
                        $request->get('format', 'rss')
        );
    }

    /**
     * returns the 'If-Modified-Since' header value
     *
     * @return \DateTime
     */
    protected function getModifiedSince()
    {
        if (is_null($this->since)) {
            $this->since = new \DateTime('@0');
        }

        return $this->since;
    }

    /**
     * Extracts the 'If-Modified-Since' value from the headers.
     *
     * @param Request $request
     * @return $this
     */
    protected function setModifiedSince(Request $request)
    {
        $this->since = new \DateTime();
        if ($request->headers->has('If-Modified-Since')) {
            $string = $request->headers->get('If-Modified-Since');
            $this->since = \DateTime::createFromFormat(\DateTime::RSS, $string);
        } else {
            $this->since->setTimestamp(1);
        }

        return $this;
    }

    /**
     * Generate the HTTP response
     * 200 : a full body containing the stream
     * 304 : Not modified
     *
     * @param $id
     * @param $format
     * @return Response
     * @throws \Exception
     */
    protected function createStreamResponse($id, $format)
    {
        $feed = $this->getFeed($id);

        if ($this->mustForceRefresh() || $feed->getLastModified() > $this->getModifiedSince()) {
            $response = new Response($this->getFeedIo()->format($feed, $format)->saveXML());
            $this->setFeedHeaders($response, $feed);
        } else {
            $response = new Response();
            $response->setNotModified();
        }

        return $response;
    }

    /**
     * @param Response $response
     * @param Feed $feed
     * @return $this
     */
    protected function setFeedHeaders(Response $response, Feed $feed)
    {
        $response->headers->set('Content-Type', 'application/xhtml+xml');
        if (! $this->isPrivate() ) {
            $response->setPublic();
        }

        $response->setMaxAge(3600);
        $response->setLastModified($feed->getLastModified());
        
        return $this;
    }

    /**
     * @return boolean true if the feed must be private
     */
    protected function isPrivate()
    {
        return $this->container->getParameter('feedio.private_feeds');
    }

    /**
     * Get the Stream's content using a FeedContentProvider
     * The FeedContentProvider instance is provided as a service
     * default : feedio.storage
     *
     * @param  mixed     $id
     * @return Feed
     * @throws FeedNotFoundException
     */
    protected function getFeed($id)
    {
        try {
            return $this->getStorage()->getFeed($id);
        } catch (FeedNotFoundException $e) {
            throw $this->createNotFoundException("feed {$id} not found");
        }
    }

    /**
     * Returns true if the controller must ignore the last modified date
     *
     * @return boolean
     */
    protected function mustForceRefresh()
    {
        if ($this->container->hasParameter(self::FORCE_PARAM_NAME))
            return $this->container->getParameter(self::FORCE_PARAM_NAME);

        return false;
    }


    protected function getContainer()
    {
        return $this->container;
    }

}
