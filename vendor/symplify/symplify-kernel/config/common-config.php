<?php

declare (strict_types=1);
namespace MonorepoBuilder20211124;

use MonorepoBuilder20211124\Symfony\Component\Console\Style\SymfonyStyle;
use MonorepoBuilder20211124\Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20211124\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory;
use MonorepoBuilder20211124\Symplify\PackageBuilder\Parameter\ParameterProvider;
use MonorepoBuilder20211124\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
use MonorepoBuilder20211124\Symplify\SmartFileSystem\FileSystemFilter;
use MonorepoBuilder20211124\Symplify\SmartFileSystem\FileSystemGuard;
use MonorepoBuilder20211124\Symplify\SmartFileSystem\Finder\FinderSanitizer;
use MonorepoBuilder20211124\Symplify\SmartFileSystem\Finder\SmartFinder;
use MonorepoBuilder20211124\Symplify\SmartFileSystem\SmartFileSystem;
use function MonorepoBuilder20211124\Symfony\Component\DependencyInjection\Loader\Configurator\service;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    // symfony style
    $services->set(\MonorepoBuilder20211124\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory::class);
    $services->set(\MonorepoBuilder20211124\Symfony\Component\Console\Style\SymfonyStyle::class)->factory([\MonorepoBuilder20211124\Symfony\Component\DependencyInjection\Loader\Configurator\service(\MonorepoBuilder20211124\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory::class), 'create']);
    // filesystem
    $services->set(\MonorepoBuilder20211124\Symplify\SmartFileSystem\Finder\FinderSanitizer::class);
    $services->set(\MonorepoBuilder20211124\Symplify\SmartFileSystem\SmartFileSystem::class);
    $services->set(\MonorepoBuilder20211124\Symplify\SmartFileSystem\Finder\SmartFinder::class);
    $services->set(\MonorepoBuilder20211124\Symplify\SmartFileSystem\FileSystemGuard::class);
    $services->set(\MonorepoBuilder20211124\Symplify\SmartFileSystem\FileSystemFilter::class);
    $services->set(\MonorepoBuilder20211124\Symplify\PackageBuilder\Parameter\ParameterProvider::class)->args([\MonorepoBuilder20211124\Symfony\Component\DependencyInjection\Loader\Configurator\service(\MonorepoBuilder20211124\Symfony\Component\DependencyInjection\ContainerInterface::class)]);
    $services->set(\MonorepoBuilder20211124\Symplify\PackageBuilder\Reflection\PrivatesAccessor::class);
};
