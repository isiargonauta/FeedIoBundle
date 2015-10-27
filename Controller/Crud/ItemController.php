<?php

namespace Debril\FeedIoBundle\Controller\Crud;

use Debril\FeedIoBundle\Controller\CrudControllerAbstract;
use Debril\FeedIoBundle\Entity\Item;
use Debril\FeedIoBundle\Form\Type\ItemType;

/**
 * Item controller.
 *
 */
class ItemController extends CrudControllerAbstract
{

    const ENTITY_NAME = 'item';

    /**
     * @return 
     */
    protected function getEntity()
    {
        return new Item();
    }
    
    /**
     * @return 
     */
    protected function getFormType()
    {
        return new ItemType();
    }

}
