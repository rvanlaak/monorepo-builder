<?php

declare (strict_types=1);
namespace MonorepoBuilder20210708\Symplify\PackageBuilder\DependencyInjection\CompilerPass;

use MonorepoBuilder20210708\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use MonorepoBuilder20210708\Symfony\Component\DependencyInjection\ContainerBuilder;
final class AutowireInterfacesCompilerPass implements \MonorepoBuilder20210708\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
{
    /**
     * @var mixed[]
     */
    private $typesToAutowire;
    /**
     * @param string[] $typesToAutowire
     */
    public function __construct(array $typesToAutowire)
    {
        $this->typesToAutowire = $typesToAutowire;
    }
    public function process(\MonorepoBuilder20210708\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder) : void
    {
        $containerBuilderDefinitions = $containerBuilder->getDefinitions();
        foreach ($containerBuilderDefinitions as $definition) {
            foreach ($this->typesToAutowire as $typeToAutowire) {
                if (!\is_a((string) $definition->getClass(), $typeToAutowire, \true)) {
                    continue;
                }
                $definition->setAutowired(\true);
                continue 2;
            }
        }
    }
}