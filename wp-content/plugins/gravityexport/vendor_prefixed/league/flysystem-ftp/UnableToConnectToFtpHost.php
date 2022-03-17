<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 25-October-2021 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace GravityKit\GravityExport\League\Flysystem\Ftp;

use RuntimeException;

final class UnableToConnectToFtpHost extends RuntimeException implements FtpConnectionException
{
    public static function forHost(string $host, int $port, bool $ssl): UnableToConnectToFtpHost
    {
        $usingSsl = $ssl ? ', using ssl' : '';

        return new UnableToConnectToFtpHost("Unable to connect to host $host at port $port$usingSsl.");
    }
}
