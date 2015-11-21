<?php

namespace Debril\FeedIoBundle\Controller;

use Debril\FeedIoBundle\Entity\Feed;
use Debril\FeedIoBundle\Form\Type\ExternalFeedType;
use Debril\FeedIoBundle\Form\Type\PublishedFeedType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FeedController extends Controller
{

    
    /**
     * lists feeds.
     */
    public function indexAction()
    {        
    
        $em = $this->getDoctrine()->getManager();
        $feeds = $em->getRepository('DebrilFeedIoBundle:Feed')->findAll();
        
        return $this->render('DebrilFeedIoBundle:Feed:index.html.twig', array(
                'feeds' => $feeds,
            ));
    }

    /**
     * displays the form made to add a new external feed.
     */
    public function addAction(Request $request)
    {
        $feed = new Feed();
        $form = $this->createForm(new ExternalFeedType(), $feed);
        $form->handleRequest($request);
        
        if ( $form->isValid() ) {
            $feed->setType(Feed::TYPE_EXTERNAL);
            $this->updateFeed($feed);

            return $this->redirect(
                        $this->generateUrl('feed_show',
                        array(
                            'id' => $feed->getId()
                            ))
                        );
        }
        
        return $this->render('DebrilFeedIoBundle:Feed:add.html.twig', array(
                'form' => $form->createView(),
            ));
    }

    /**
     * displays the form made to create a new feed.
     */
    public function newAction(Request $request)
    {        
        $feed = new Feed();
        $form = $this->createForm(new PublishedFeedType(), $feed);
               
        $form->handleRequest($request);
        
        if ( $form->isValid() ) {
            $feed->setLastModified(new \DateTime);
            $feed->setLink('');
            $this->saveFeed($feed);

            return $this->redirect(
                        $this->generateUrl('feed_show',
                        array(
                            'id' => $feed->getId()
                            ))
                        );
        } 
        
        return $this->render('DebrilFeedIoBundle:Feed:new.html.twig', array(
                'form' => $form->createView(),
            ));
    }

    /**
     * displays a feed.
     * @param integer $id
     */
    public function showAction($id)
    {        
        $feed = $this->getFeedById($id);

        if (!$feed) {
            throw $this->createNotFoundException('Unable to find feed.');
        }

        return $this->render('DebrilFeedIoBundle:Feed:show.html.twig', array(
                'feed' => $feed,
                'items' => $feed->getItemIterator(),
                'showUpdateLink' => $feed->getType() === Feed::TYPE_EXTERNAL,
            ));
    }

    /**
     * displays the form made to edit an existing feed.
     */
    public function editAction(Request $request, $id)
    {        
        $feed = $this->getFeedById($id);

        if (!$feed) {
            throw $this->createNotFoundException('Unable to find feed.');
        }
        $form = $this->createForm(new PublishedFeedType(), $feed);
        $form->handleRequest($request);
        
        if ( $form->isValid() ) {
            $this->saveFeed($feed);
            
            $this->addFlash('notice', "{$feed->getTitle()} successfully updated");
                
            return $this->redirect(
                        $this->generateUrl('feed_show',
                        array(
                            'id' => $feed->getId()
                            ))
                        );
        }
        
        return $this->render('DebrilFeedIoBundle:Feed:edit.html.twig', array(
                'form' => $form->createView(),
            ));
    }

    /**
     * updates an external feed.
     */
    public function updateAction($id)
    {
        $feed = $this->getFeedById($id);
        $this->updateFeed($feed);
        
        $this->addFlash('notice', "Feed was successfully updated");
        
        return $this->redirect(
                    $this->generateUrl('feed_show',
                    array(
                        'id' => $feed->getId()
                        ))
                    );
    }
    
    /**
     * @return FeedIo
     */
    protected function getFeedIo()
    {
        return $this->get('feedio');
    }
    
    /**
     * @return Feed
     */
    protected function getFeedById($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        return $em->getRepository('DebrilFeedIoBundle:Feed')->find($id);
    }
    
    /**
     * @param Feed $feed 
     * @return Feed
     */
    protected function updateFeed(Feed $feed)
    {
        $this->getFeedIo()->read($feed->getUrl(), $feed, $feed->getLastModified());

        return $this->saveFeed($feed);
    }
 
    /**
     * @param Feed $feed 
     * @return Feed
     */
    protected function saveFeed(Feed $feed)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($feed);
        $em->flush();  
    
        return $feed;
    }
}
