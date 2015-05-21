<?php

namespace FeedIo;

use \FeedIo\Feed;
use \FeedIo\Reader\Result;
use \FeedIo\Adapter\NullResponse;

class FeedIoMock extends \FeedIo\FeedIo
{

    /**
     * @var integer read() calls count
     */
    protected $readCount = 0;
    
    /**
     * @param string $url feed URL
     * @param FeedInterface $feed
     * @param \DateTime $modifiedSince
     * @return Result
     */
    public function read($url, FeedInterface $feed = null, \DateTime $modifiedSince = null)
    {
        $this->readCount++;
        
        return new Result(
            new \DomDocument,
            new Feed,
            new \DateTime,
            new NullResponse,
            $url
        );
 
    }

    /**
     * @return integer
     */
    public function getReadCount()
    {
        return $this->readCount;
    }

}
