<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20211124\Symfony\Component\DependencyInjection\Compiler;

use MonorepoBuilder20211124\Symfony\Component\DependencyInjection\Definition;
use MonorepoBuilder20211124\Symfony\Component\DependencyInjection\Exception\RuntimeException;
use MonorepoBuilder20211124\Symfony\Component\DependencyInjection\Reference;
/**
 * Checks the validity of references.
 *
 * The following checks are performed by this pass:
 * - target definitions are not abstract
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class CheckReferenceValidityPass extends \MonorepoBuilder20211124\Symfony\Component\DependencyInjection\Compiler\AbstractRecursivePass
{
    /**
     * @param bool $isRoot
     */
    protected function processValue($value, $isRoot = \false)
    {
        if ($isRoot && $value instanceof \MonorepoBuilder20211124\Symfony\Component\DependencyInjection\Definition && ($value->isSynthetic() || $value->isAbstract())) {
            return $value;
        }
        if ($value instanceof \MonorepoBuilder20211124\Symfony\Component\DependencyInjection\Reference && $this->container->hasDefinition((string) $value)) {
            $targetDefinition = $this->container->getDefinition((string) $value);
            if ($targetDefinition->isAbstract()) {
                throw new \MonorepoBuilder20211124\Symfony\Component\DependencyInjection\Exception\RuntimeException(\sprintf('The definition "%s" has a reference to an abstract definition "%s". Abstract definitions cannot be the target of references.', $this->currentId, $value));
            }
        }
        return parent::processValue($value, $isRoot);
    }
}
