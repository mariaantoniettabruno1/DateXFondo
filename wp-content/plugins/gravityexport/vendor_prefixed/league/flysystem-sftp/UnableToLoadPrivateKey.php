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

class UnableToLoadPrivateKey extends RuntimeException implements FilesystemException
{
    public function __construct(string $message = "Unable to load private key.")
    {
        parent::__construct($message);
    }
}
