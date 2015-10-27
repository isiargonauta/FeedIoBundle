<?php

namespace Debril\FeedIoBundle\Controller\Crud;

use Debril\FeedIoBundle\Controller\CrudControllerAbstract;
use Debril\FeedIoBundle\Entity\Media;
use Debril\FeedIoBundle\Form\Type\MediaType;

/**
 * Media controller.
 *
 */
class MediaController extends CrudControllerAbstract
{

    const ENTITY_NAME = 'media';

    /**
     * @return 
     */
    protected function getEntity()
    {
        return new Media();
    }
    
    /**
     * @return 
     */
    protected function getFormType()
    {
        return new MediaType();
    }

}
