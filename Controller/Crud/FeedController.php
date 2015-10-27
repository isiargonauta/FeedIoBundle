<?php

namespace Debril\FeedIoBundle\Controller\Crud;

use Debril\FeedIoBundle\Controller\CrudControllerAbstract;
use Debril\FeedIoBundle\Entity\Feed;
use Debril\FeedIoBundle\Form\Type\FeedType;

/**
 * Feed controller.
 *
 */
class FeedController extends CrudControllerAbstract
{

    const ENTITY_NAME = 'feed';

    /**
     * @return 
     */
    protected function getEntity()
    {
        return new Feed();
    }
    
    /**
     * @return 
     */
    protected function getFormType()
    {
        return new FeedType();
    }

}
