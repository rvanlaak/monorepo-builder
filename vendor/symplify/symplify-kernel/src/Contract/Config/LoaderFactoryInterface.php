<?php

declare (strict_types=1);
namespace MonorepoBuilder20211124\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilder20211124\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder20211124\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder
     * @param string $currentWorkingDirectory
     */
    public function create($containerBuilder, $currentWorkingDirectory) : \MonorepoBuilder20211124\Symfony\Component\Config\Loader\LoaderInterface;
}
