<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 25-October-2021 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace GravityKit\GravityExport\League\Flysystem\Ftp;

interface ConnectionProvider
{
    /**
     * @return resource
     */
    public function createConnection(FtpConnectionOptions $options);
}
