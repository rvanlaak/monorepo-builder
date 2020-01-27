<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Merge\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\MonorepoBuilder\ComposerJsonObject\ComposerJsonFactory;
use Symplify\MonorepoBuilder\ComposerJsonObject\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Console\Reporter\ConflictingPackageVersionsReporter;
use Symplify\MonorepoBuilder\Exception\ShouldNotHappenException;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\FileSystem\JsonFileManager;
use Symplify\MonorepoBuilder\Merge\ComposerJsonMerger;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
use Symplify\MonorepoBuilder\VersionValidator;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\PackageBuilder\Console\ShellCode;
use Symplify\SmartFileSystem\SmartFileInfo;

final class MergeCommand extends Command
{
    /**
     * @var ComposerJsonDecoratorInterface[]
     */
    private $composerJsonDecorators = [];

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    /**
     * @var ComposerJsonMerger
     */
    private $composerJsonMerger;

    /**
     * @var VersionValidator
     */
    private $versionValidator;

    /**
     * @var ComposerJsonProvider
     */
    private $composerJsonProvider;

    /**
     * @var ConflictingPackageVersionsReporter
     */
    private $conflictingPackageVersionsReporter;

    /**
     * @var ComposerJsonFactory
     */
    private $composerJsonFactory;

    /**
     * @var JsonFileManager
     */
    private $jsonFileManager;

    /**
     * @param ComposerJsonDecoratorInterface[] $composerJsonDecorators
     */
    public function __construct(
        SymfonyStyle $symfonyStyle,
        ComposerJsonMerger $composerJsonMerger,
        VersionValidator $versionValidator,
        ComposerJsonProvider $composerJsonProvider,
        ConflictingPackageVersionsReporter $conflictingPackageVersionsReporter,
        ComposerJsonFactory $composerJsonFactory,
        JsonFileManager $jsonFileManager,
        array $composerJsonDecorators
    ) {
        $this->symfonyStyle = $symfonyStyle;
        $this->composerJsonMerger = $composerJsonMerger;
        $this->versionValidator = $versionValidator;
        $this->composerJsonProvider = $composerJsonProvider;
        $this->conflictingPackageVersionsReporter = $conflictingPackageVersionsReporter;
        $this->composerJsonFactory = $composerJsonFactory;
        $this->jsonFileManager = $jsonFileManager;
        $this->composerJsonDecorators = $composerJsonDecorators;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Merge "composer.json" from all found packages to root one');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->ensureNoConflictingPackageVersions();

        $packageFileInfos = $this->composerJsonProvider->getPackagesFileInfos();
        $mergedComposerJson = $this->mergePackageFileInfosAndDecorate($packageFileInfos);

        $this->mergeExistingComposerJsonAndMergedComposerJson($mergedComposerJson);

        return ShellCode::SUCCESS;
    }

    private function ensureNoConflictingPackageVersions(): void
    {
        $conflictingPackageVersions = $this->versionValidator->findConflictingPackageVersionsInFileInfos(
            $this->composerJsonProvider->getPackagesFileInfos()
        );

        if (count($conflictingPackageVersions) === 0) {
            return;
        }

        $this->conflictingPackageVersionsReporter->report($conflictingPackageVersions);

        throw new ShouldNotHappenException('Fix conflicting package version first');
    }

    private function mergeExistingComposerJsonAndMergedComposerJson(ComposerJson $mergedComposerJson): void
    {
        $mainComposerJsonFilePath = getcwd() . '/composer.json';

        $mainComposerJson = $this->composerJsonFactory->createFromFilePath($mainComposerJsonFilePath);

        $this->composerJsonMerger->mergeJsonToRoot($mainComposerJson, $mergedComposerJson);

        $this->jsonFileManager->saveComposerJsonToFilePath($mainComposerJson, $mainComposerJsonFilePath);

        $this->symfonyStyle->success('Main "composer.json" was updated.');
    }

    /**
     * @param SmartFileInfo[] $packageFileInfos
     */
    private function mergePackageFileInfosAndDecorate(array $packageFileInfos): ComposerJson
    {
        $mergedComposerJson = $this->composerJsonMerger->mergeFileInfos($packageFileInfos);
        foreach ($this->composerJsonDecorators as $composerJsonDecorator) {
            $composerJsonDecorator->decorate($mergedComposerJson);
        }

        return $mergedComposerJson;
    }
}
