<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symplify\SymfonyStaticDumper\Routing\ControllerMatcher;
use Symplify\SymfonyStaticDumper\ControllerWithDataProviderMatcher;
use Symplify\SymfonyStaticDumper\ValueObject\SymfonyStaticDumperConfig;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SymfonyStaticDumperConfig::FILE_PATH);

    $services = $containerConfigurator->services();
    $services->set(ControllerWithDataProviderMatcher::class)
        ->arg('$controllerMatcher', service(ControllerMatcher::class))
        ->arg('$controllerWithDataProviders', []);
};
