<?php

namespace Icse\MembersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\DataTransformer\DataTransformerChain;
use Icse\MembersBundle\Form\DataTransformer\NullToIncompleteArrayTransformer;


class DateTimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($builder->has('time'))
        {
            $timeOptions = $builder->get('time')->getOptions();
            $builder->remove('time');
            $builder->add('time', 'time12', $timeOptions);

            $original_transformers = $builder->getViewTransformers();
            assert(count($original_transformers) == 1, 'n transformers == 1');
            $chain = $original_transformers[0];
            assert($chain instanceof DataTransformerChain, 'instanceof DataTransformerChain');
            $children = $chain->getTransformers();
            assert(count($children) == 2, 'n children == 2');

            $builder->resetViewTransformers();
            $builder->addViewTransformer(new DataTransformerChain([
                $children[0],
                new NullToIncompleteArrayTransformer,
                $children[1],
            ]));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'with_seconds' => true,
        ]);
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
