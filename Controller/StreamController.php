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

use Debril\FeedIoBundle\Exception\InvalidStorageException;
use Debril\FeedIoBundle\Exception\FeedNotFoundException;
use Debril\FeedIoBundle\Adapter\StorageInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class StreamController extends Controller
{

    /**
     * default provider
     */
    const DEFAULT_SOURCE = 'feedio.storage';

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
                        $request->get('format', 'rss'), 
                        $request->get('source', self::DEFAULT_SOURCE)
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
     * @param string $source
     * @return Response
     * @throws \Exception
     */
    protected function createStreamResponse($id, $format, $source = self::DEFAULT_SOURCE)
    {
        $content = $this->getContent($id, $source);

        if ($this->mustForceRefresh() || $content->getLastModified() > $this->getModifiedSince()) {
            $response = new Response($this->getFeedIo()->format($content, $format)->saveXML());
            $response->headers->set('Content-Type', 'application/xhtml+xml');

            $response->setPublic();
            $response->setMaxAge(3600);
            $response->setLastModified($content->getLastModified());
        } else {
            $response = new Response();
            $response->setNotModified();
        }

        return $response;
    }

    /**
     * Get the Stream's content using a FeedContentProvider
     * The FeedContentProvider instance is provided as a service
     * default : debril.provider.service
     *
     * @param  mixed                                      $id
     * @param  string                                     $source
     * @return \FeedIo\Feed
     * @throws \Exception
     */
    protected function getContent($id, $source)
    {
        $storage = $this->get($source);

        if ( !$storage instanceof StorageInterface ) {
            throw new InvalidStorageException('Storage is not a StorageInterface instance');
        }

        try {
            return $storage->getFeed($id);
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

    /**
     * @return \FeedIo\FeedIo
     */
    protected function getFeedIo()
    {
        return $this->get('feedio');
    }

}
