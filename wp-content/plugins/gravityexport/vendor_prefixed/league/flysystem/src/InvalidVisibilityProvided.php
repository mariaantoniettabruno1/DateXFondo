<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 25-October-2021 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace GravityKit\GravityExport\League\Flysystem;

use InvalidArgumentException;

use function var_export;

class InvalidVisibilityProvided extends InvalidArgumentException implements FilesystemException
{
    public static function withVisibility(string $visibility, string $expectedMessage): InvalidVisibilityProvided
    {
        $provided = var_export($visibility, true);
        $message = "Invalid visibility provided. Expected {$expectedMessage}, received {$provided}";

        throw new InvalidVisibilityProvided($message);
    }
}
