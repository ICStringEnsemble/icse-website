<?php

namespace Icse\MembersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;


class TimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ('single_text' === $options['widget'])
        {
        
//            $format = 'H';
            $format = 'g';

            if ($options['with_minutes'])
            {
                $format .= ':i';
            }

            if ($options['with_seconds'])
            {
                $format .= ':s';
            }
            
            $format .= ' a';
        
            $builder->resetViewTransformers();
            $builder->addViewTransformer(new DateTimeToStringTransformer($options['model_timezone'], $options['view_timezone'], $format));
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
