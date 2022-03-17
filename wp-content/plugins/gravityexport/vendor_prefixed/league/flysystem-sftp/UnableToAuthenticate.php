<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 25-October-2021 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace GravityKit\GravityExport\League\Flysystem\PhpseclibV2;

use GravityKit\GravityExport\League\Flysystem\FilesystemException;
use RuntimeException;

class UnableToAuthenticate extends RuntimeException implements FilesystemException
{
    public static function withPassword(): UnableToAuthenticate
    {
        return new UnableToAuthenticate('Unable to authenticate using a password.');
    }

    public static function withPrivateKey(): UnableToAuthenticate
    {
        return new UnableToAuthenticate('Unable to authenticate using a private key.');
    }

    public static function withSshAgent(): UnableToAuthenticate
    {
        return new UnableToAuthenticate('Unable to authenticate using an SSH agent.');
    }
}
