<?php

namespace Icse\MembersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FileInfoType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name')
      ->add('category')
      ->add('file', 'hidden');
  }

  public function getName()
  {
      return 'file';
  }
}
