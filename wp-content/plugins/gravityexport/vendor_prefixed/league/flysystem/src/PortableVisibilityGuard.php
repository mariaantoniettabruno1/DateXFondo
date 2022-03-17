<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 25-October-2021 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace GravityKit\GravityExport\League\Flysystem;

final class PortableVisibilityGuard
{
    public static function guardAgainstInvalidInput(string $visibility): void
    {
        if ($visibility !== Visibility::PUBLIC && $visibility !== Visibility::PRIVATE) {
            $className = Visibility::class;
            throw InvalidVisibilityProvided::withVisibility(
                $visibility,
                "either {$className}::PUBLIC or {$className}::PRIVATE"
            );
        }
    }
}
