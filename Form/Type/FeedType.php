<?php

namespace Debril\FeedIoBundle\Form\Type;

use Debril\FeedIoBundle\Entity\Feed;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeedType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $feed = new Feed;
        $builder
            ->add('type', 'choice', array('choices' => $feed->getAvailableTypes()))
            ->add('comment')
            ->add('publicId')
            ->add('description')
            ->add('title')
            ->add('link')
            ->add('lastModified')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Debril\FeedIoBundle\Entity\Feed'
        ));
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'debril_feediobundle_feed';
    }
}
