<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator;

use MonorepoBuilder20211124\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
final class FilterOutDuplicatedRequireAndRequireDevJsonDecorator implements \Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface
{
    /**
     * @param \Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson
     */
    public function decorate($composerJson) : void
    {
        if ($composerJson->getRequire() === []) {
            return;
        }
        if ($composerJson->getRequireDev() === []) {
            return;
        }
        $duplicatedPackages = $composerJson->getDuplicatedRequirePackages();
        $currentRequireDev = $composerJson->getRequireDev();
        $packages = \array_keys($currentRequireDev);
        foreach ($packages as $package) {
            if (\in_array($package, $duplicatedPackages, \true)) {
                unset($currentRequireDev[$package]);
            }
        }
        $composerJson->setRequireDev($currentRequireDev);
    }
}
