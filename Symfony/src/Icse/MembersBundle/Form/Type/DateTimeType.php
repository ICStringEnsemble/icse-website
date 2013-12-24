<?php

namespace Icse\MembersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class DateTimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($builder->has('time'))
        {
            $timeOptions = $builder->get('time')->getOptions();
            $builder->remove('time');
            $builder->add('time', 'time12', $timeOptions);
        }
    }

    public function getParent()
    {
      return 'datetime';
    }

    public function getName()
    {
      return 'datetime12';
    }
}
