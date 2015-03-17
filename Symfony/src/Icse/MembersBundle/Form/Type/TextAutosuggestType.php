<?php

namespace Icse\MembersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class TextAutosuggestType extends AbstractType {

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'icse_text_autosuggest';
    }
}