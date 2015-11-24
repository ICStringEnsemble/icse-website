<?php

namespace Icse\MembersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;


class DocEditorType extends AbstractType {

    public function getParent()
    {
        return 'textarea';
    }

    public function getName()
    {
        return 'icse_doc_editor';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'doceditor_style' => 'normal'
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['doceditor_style'] = $options['doceditor_style'];
    }

}