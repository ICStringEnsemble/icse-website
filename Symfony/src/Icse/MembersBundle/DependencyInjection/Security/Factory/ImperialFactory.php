<?php

namespace Icse\MembersBundle\DependencyInjection\Security\Factory;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


class ImperialFactory extends \Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FormLoginFactory
{
    public function getKey()
    {
        return 'imperial-form-login';
    }

    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $provider = 'security.authentication.provider.imperial.'.$id;
        $container
            ->setDefinition($provider, new DefinitionDecorator('security.authentication.provider.imperial'))
            ->replaceArgument(0, new Reference($userProviderId))
            ->replaceArgument(2, $id)
        ;

        return $provider;
    }
}
