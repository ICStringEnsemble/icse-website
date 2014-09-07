<?php

namespace Icse\MembersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Icse\MembersBundle\Form\DataTransformer\NullableTimeToStringTransformer;


class TimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ('single_text' === $options['widget'])
        {
            $format = 'g';

            if ($options['with_minutes'])
            {
                $format .= ':i';
            }

            $format .= ' a';

            $builder->resetViewTransformers();
            $builder->addViewTransformer(new NullableTimeToStringTransformer($options['model_timezone'], $options['view_timezone'], $format));
        }
    }

    public function getParent()
    {
        return 'time';
    }

    public function getName()
    {
        return 'time12';
    }
}
