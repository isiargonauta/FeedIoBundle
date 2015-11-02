<?php

namespace Debril\FeedIoBundle\Controller;

use Debril\FeedIoBundle\Entity\Feed;
use Debril\FeedIoBundle\Form\Type\ExternalFeedType;
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
            
            $this->getFeedIo()->read($feed->getLink(), $feed);
            $feed->setType(Feed::TYPE_EXTERNAL);         

            $em = $this->getDoctrine()->getManager();
            $em->persist($feed);
            $em->flush();

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
    public function newAction()
    {
        return $this->render('DebrilFeedIoBundle:Feed:new.html.twig', array(
                // ...
            ));
    }

    /**
     * saves a new or an updated feed.
     */
    public function saveAction(Request $request)
    {        
        $feed = new Feed();
        $form = $this->createForm(new ExternalFeedType(), $feed, array(
            'action' => $this->generateUrl('feed_save'),
            'method' => 'POST',
        ));
        $form->handleRequest($request);
        
        return $this->render('DebrilFeedIoBundle:Feed:save.html.twig', array(
                // ...
            ));
    }

    /**
     * displays a feed.
     * @param integer $id
     */
    public function showAction($id)
    {        
        $em = $this->getDoctrine()->getManager();
        $feed = $em->getRepository('DebrilFeedIoBundle:Feed')->find($id);

        if (!$feed) {
            throw $this->createNotFoundException('Unable to find feed.');
        }

        return $this->render('DebrilFeedIoBundle:Feed:show.html.twig', array(
                'feed' => $feed,
                'items' => $feed->getItemIterator(),
            ));
    }

    /**
     * displays the form made to edit an existing feed.
     */
    public function editAction()
    {
        return $this->render('DebrilFeedIoBundle:Feed:edit.html.twig', array(
                // ...
            ));
    }

    /**
     * updates an external feed.
     */
    public function updateAction()
    {
        return $this->render('DebrilFeedIoBundle:Feed:update.html.twig', array(
                // ...
            ));
    }
    
    /**
     * @return FeedIo
     */
    protected function getFeedIo()
    {
        return $this->get('feedio');
    }
}
