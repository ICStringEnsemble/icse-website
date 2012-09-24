<?php

namespace Icse\MembersBundle;

use Icse\MembersBundle\DependencyInjection\Security\Factory\ImperialFactory;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder; 

class IcseMembersBundle extends Bundle
{
  public function build(ContainerBuilder $container)
    {
      parent::build($container);

      $extension = $container->getExtension('security');
      $extension->addSecurityListenerFactory(new ImperialFactory());
    } 
}
