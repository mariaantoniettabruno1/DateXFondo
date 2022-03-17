<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 25-October-2021 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace GravityKit\GravityExport\League\Flysystem\Ftp;

use TypeError;

class NoopCommandConnectivityChecker implements ConnectivityChecker
{
    public function isConnected($connection): bool
    {
        // @codeCoverageIgnoreStart
        try {
            $response = @ftp_raw($connection, 'NOOP');
        } catch (TypeError $typeError) {
            return false;
        }
        // @codeCoverageIgnoreEnd

        $responseCode = $response ? (int) preg_replace('/\D/', '', implode('', $response)) : false;

        return $responseCode === 200;
    }
}
