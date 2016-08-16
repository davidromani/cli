<?php

/*
 * This file is part of the puli/cli package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Puli\Cli\Handler;

use Puli\Manager\Api\Module\RootModuleFileManager;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

/**
 * Handles the "upgrade" command.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class UpgradeCommandHandler
{
    /**
     * @var RootModuleFileManager
     */
    private $moduleFileManager;

    /**
     * Creates the command handler.
     *
     * @param RootModuleFileManager $moduleFileManager The manager of the
     *                                                 puli.json file
     */
    public function __construct(RootModuleFileManager $moduleFileManager)
    {
        $this->moduleFileManager = $moduleFileManager;
    }

    /**
     * Handles the "upgrade" command.
     *
     * @param Args $args The console arguments
     * @param IO   $io   The I/O
     *
     * @return int The status code
     */
    public function handle(Args $args, IO $io)
    {
        $moduleFile = $this->moduleFileManager->getModuleFile();
        $originVersion = $moduleFile->getVersion();
        $targetVersion = $args->getArgument('version');

        if (version_compare($originVersion, $targetVersion, '=')) {
            $io->writeLine(sprintf('Your puli.json is already at version %s.', $targetVersion));

            return 0;
        }

        $this->moduleFileManager->migrate($targetVersion);

        $io->writeLine(sprintf(
            'Migrated your puli.json from version %s to version %s.',
            $originVersion,
            $targetVersion
        ));

        return 0;
    }
}
