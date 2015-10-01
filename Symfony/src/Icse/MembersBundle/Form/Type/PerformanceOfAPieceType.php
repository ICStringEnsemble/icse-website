<?php

namespace Icse\MembersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PerformanceOfAPieceType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('piece', 'hidden_entity', [
            'class' => 'Icse\PublicBundle\Entity\PieceOfMusic'
        ]);
        $builder->add('sort_index', 'hidden');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Icse\PublicBundle\Entity\PerformanceOfAPiece',
        ]);
    }

    public function getName()
    {
        return 'icse_event_performance';
    }
}