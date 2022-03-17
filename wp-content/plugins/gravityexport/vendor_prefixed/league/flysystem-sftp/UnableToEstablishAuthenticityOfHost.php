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

class UnableToEstablishAuthenticityOfHost extends RuntimeException implements FilesystemException
{
    public static function becauseTheAuthenticityCantBeEstablished(string $host): UnableToEstablishAuthenticityOfHost
    {
        return new UnableToEstablishAuthenticityOfHost("The authenticity of host $host can't be established.");
    }
}
