<?php

declare (strict_types=1);
namespace MonorepoBuilder20211124\Symplify\SymplifyKernel\Config\Loader;

use MonorepoBuilder20211124\Symfony\Component\Config\FileLocator;
use MonorepoBuilder20211124\Symfony\Component\Config\Loader\DelegatingLoader;
use MonorepoBuilder20211124\Symfony\Component\Config\Loader\GlobFileLoader;
use MonorepoBuilder20211124\Symfony\Component\Config\Loader\LoaderResolver;
use MonorepoBuilder20211124\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20211124\Symplify\PackageBuilder\DependencyInjection\FileLoader\ParameterMergingPhpFileLoader;
use MonorepoBuilder20211124\Symplify\SymplifyKernel\Contract\Config\LoaderFactoryInterface;
final class ParameterMergingLoaderFactory implements \MonorepoBuilder20211124\Symplify\SymplifyKernel\Contract\Config\LoaderFactoryInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder
     * @param string $currentWorkingDirectory
     */
    public function create($containerBuilder, $currentWorkingDirectory) : \MonorepoBuilder20211124\Symfony\Component\Config\Loader\LoaderInterface
    {
        $fileLocator = new \MonorepoBuilder20211124\Symfony\Component\Config\FileLocator([$currentWorkingDirectory]);
        $loaders = [new \MonorepoBuilder20211124\Symfony\Component\Config\Loader\GlobFileLoader($fileLocator), new \MonorepoBuilder20211124\Symplify\PackageBuilder\DependencyInjection\FileLoader\ParameterMergingPhpFileLoader($containerBuilder, $fileLocator)];
        $loaderResolver = new \MonorepoBuilder20211124\Symfony\Component\Config\Loader\LoaderResolver($loaders);
        return new \MonorepoBuilder20211124\Symfony\Component\Config\Loader\DelegatingLoader($loaderResolver);
    }
}
