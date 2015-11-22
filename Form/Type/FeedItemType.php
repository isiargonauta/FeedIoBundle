<?php

namespace Debril\FeedIoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeedItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('publishedAt')
            ->add('title')
            ->add('publicId')
            ->add('description', 'ckeditor', array(
                'config' => array(
                    'toolbar' => array(
                            array(
                                'name' => 'clipboard',
                                'items' => array('Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'), ),
                            array(
                                'name' => 'basicstyles',
                                'items' => array('Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'),
                            ),
                            array(
                                'name' => 'paragraph',
                                'items' => array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'), ),

                            array(
                                'name' => 'links',
                                'items' => array('Link', 'Unlink', 'Anchor'), ),
                            array(
                                'name' => 'tools',
                                'items' => array('Maximize', 'ShowBlocks', '-', 'About'), ),
                        ),
                    ),
                )
            )
            ->add('submit', 'submit', array('label' => 'Save'))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Debril\FeedIoBundle\Entity\Item',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'debril_feediobundle_feed_item';
    }
}
