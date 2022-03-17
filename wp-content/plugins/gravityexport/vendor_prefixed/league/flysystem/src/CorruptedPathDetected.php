<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 25-October-2021 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GravityKit\GravityExport\League\Flysystem;

use RuntimeException;

final class CorruptedPathDetected extends RuntimeException implements FilesystemException
{
    public static function forPath(string $path): CorruptedPathDetected
    {
        return new CorruptedPathDetected("Corrupted path detected: " . $path);
    }
}
