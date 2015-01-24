<?php

namespace Icse\MembersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class EndTimeType extends AbstractType {

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'widget' => 'single_text',
            'label' => 'Duration',
        ]);
    }

    public function getParent()
    {
        return 'datetime';
    }

    public function getName()
    {
        return 'icse_end_time';
    }
}