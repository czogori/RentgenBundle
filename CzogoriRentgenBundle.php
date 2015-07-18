<?php

namespace Czogori\RentgenBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Rentgen\DependencyInjection\RentgenExtension;
use Czogori\RentgenBundle\DependencyInjection\Compiler\ConnectionPass;

class CzogoriRentgenBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $rentgenExtension = new RentgenExtension();
        $container->registerExtension($rentgenExtension);
        $container->loadFromExtension($rentgenExtension->getAlias());
        $container->addCompilerPass(new ConnectionPass());
    }
}
