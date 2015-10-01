<?php

namespace Icse\MembersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EndTimeType extends AbstractType {

    public function configureOptions(OptionsResolver $resolver)
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