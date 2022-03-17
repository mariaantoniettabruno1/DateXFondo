<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 25-October-2021 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace GravityKit\GravityExport\League\Flysystem\UnixVisibility;

interface VisibilityConverter
{
    public function forFile(string $visibility): int;
    public function forDirectory(string $visibility): int;
    public function inverseForFile(int $visibility): string;
    public function inverseForDirectory(int $visibility): string;
    public function defaultForDirectories(): int;
}
