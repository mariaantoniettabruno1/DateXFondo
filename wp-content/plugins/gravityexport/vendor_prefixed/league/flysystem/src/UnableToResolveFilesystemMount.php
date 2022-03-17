<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 25-October-2021 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace GravityKit\GravityExport\League\Flysystem;

use RuntimeException;

class UnableToResolveFilesystemMount extends RuntimeException implements FilesystemException
{
    public static function becauseTheSeparatorIsMissing(string $path): UnableToResolveFilesystemMount
    {
        return new UnableToResolveFilesystemMount("Unable to resolve the filesystem mount because the path ($path) is missing a separator (://).");
    }

    public static function becauseTheMountWasNotRegistered(string $mountIdentifier): UnableToResolveFilesystemMount
    {
        return new UnableToResolveFilesystemMount("Unable to resolve the filesystem mount because the mount ($mountIdentifier) was not registered.");
    }
}
