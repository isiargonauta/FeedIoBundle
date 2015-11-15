<?php

namespace Debril\FeedIoBundle\Controller;

use Debril\FeedIoBundle\Entity\Feed;
use Debril\FeedIoBundle\Entity\Item;
use Debril\FeedIoBundle\Form\Type\FeedItemType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ItemController extends Controller
{
    public function listAction($feedId)
    {
        $feed = $this->getFeedById($feedId);
        $items = $this->getItemsOfFeed($feedId);

        return $this->render('DebrilFeedIoBundle:Item:list.html.twig', array(
                'feed' => $feed,
                'items' => $items,
            ));   
    }

    public function newAction(Request $request, $feedId)
    {
        $feed = $this->getFeedById($feedId);
        $item = new Item();
        
        $form = $this->createForm(new FeedItemType(), $item);
        $form->handleRequest($request);
        
        if ( $form->isValid() ) {
            
            $feed->add($item);
            $this->saveFeed($feed);
            
            $this->addFlash('notice', "{$item->getTitle()} was successfully added to {$feed->getTitle()}");
                
            return $this->redirect(
                        $this->generateUrl('feed_show',
                        array(
                            'id' => $feed->getId()
                            ))
                        );
        }
        
        return $this->render('DebrilFeedIoBundle:Item:new.html.twig', array(
                'feed' => $feed,
                'form' => $form->createView(),
            ));    
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $item = $this->getItemById($id);

        if (!$item) {
            throw $this->createNotFoundException('Unable to find item.');
        }
        $form = $this->createForm(new FeedItemType(), $item);
        $form->handleRequest($request);

        if ( $form->isValid() ) {
            $feed = $item->getFeed();
            $this->saveFeed($feed);

            $this->addFlash('notice', "{$item->getTitle()} successfully updated");
        }

        return $this->render('DebrilFeedIoBundle:Item:edit.html.twig', array(
                'item' => $item,
                'form' => $form->createView(),
            ));
    }

    public function deleteAction()
    {
        return $this->render('DebrilFeedIoBundle:Item:delete.html.twig', array(
                // ...
            ));    }

    /**
     * @param $id
     * @return Item
     */
    protected function getItemById($id)
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('DebrilFeedIoBundle:Item')->find($id);
    }

    protected function getItemsOfFeed($feed)
    {
        $em = $this->getDoctrine()->getManager();
        
        return $em->getRepository('DebrilFeedIoBundle:Item')->findBy(array('feed' => $feed));
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
    protected function saveFeed(Feed $feed)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($feed);
        $em->flush();  
    
        return $feed;
    }

}
