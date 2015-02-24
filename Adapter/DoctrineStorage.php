<?php

/**
 * FeedIoBundle
 *
 * @package FeedIoBundle/Adapter
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2015, Alexandre Debril
 *
 */

namespace Debril\FeedIoBundle\Adapter;

use \Doctrine\Bundle\DoctrineBundle\Registry;
use \FeedIo\FeedInterface;

class DoctrineFeedContentProvider implements StorageInterface
{

    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected $doctrine;

    /**
     * @var string
     */
    protected $repositoryName;

    /**
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Returns the name of the doctrine repository
     * @return string
     */
    public function getRepositoryName()
    {
        return $this->repositoryName;
    }

    /**
     * Sets the doctrine's repository name
     * @param  string                                                     $repositoryName
     * @return \Debril\FeedIoBundle\Adapter\DoctrineStorage
     */
    public function setRepositoryName($repositoryName)
    {
        $this->repositoryName = $repositoryName;

        return $this;
    }

    /**
     * @param $id
     * @return \FeedIo\FeedInterface
     * @throws FeedNotFoundException
     */
    public function getFeed($id)
    {
        // fetch feed from data repository
        $feed = $this->getDoctrine()
                ->getManager()
                ->getRepository($this->getRepositoryName())
                ->findOneById($id);

        // if the feed is an actual FeedInterface instance, then return it
        if ( $feed instanceof FeedInterface ) {
            return $feed;
        }

        // $feed is null, which means no Feed was found with this id.
        throw new FeedNotFoundException();
    }

    /**
     * @param \FeedIo\FeedInterface $feed
     * @return $this
     */
    public function save(FeedInterface $feed)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($feed);
        $manager->flush();

        return $this;
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

}
