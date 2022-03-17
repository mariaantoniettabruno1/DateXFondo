<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 25-October-2021 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace GravityKit\GravityExport\League\Flysystem\PhpseclibV2;

use GravityKit\GravityExport\phpseclib\Net\SFTP;

class SimpleConnectivityChecker implements ConnectivityChecker
{
    public function isConnected(SFTP $connection): bool
    {
        return $connection->isConnected();
    }
}
