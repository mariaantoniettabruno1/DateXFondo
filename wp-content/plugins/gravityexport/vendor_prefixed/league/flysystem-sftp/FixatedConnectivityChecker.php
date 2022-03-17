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

class FixatedConnectivityChecker implements ConnectivityChecker
{
    /**
     * @var int
     */
    private $succeedAfter;

    /**
     * @var int
     */
    private $numberOfTimesChecked = 0;

    public function __construct(int $succeedAfter = 0)
    {
        $this->succeedAfter = $succeedAfter;
    }

    public function isConnected(SFTP $connection): bool
    {
        if ($this->numberOfTimesChecked >= $this->succeedAfter) {
            return true;
        }

        $this->numberOfTimesChecked++;

        return false;
    }
}
