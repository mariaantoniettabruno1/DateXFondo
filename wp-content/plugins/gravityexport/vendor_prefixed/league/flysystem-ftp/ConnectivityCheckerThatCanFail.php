<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 25-October-2021 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace GravityKit\GravityExport\League\Flysystem\Ftp;

class ConnectivityCheckerThatCanFail implements ConnectivityChecker
{
    /**
     * @var bool
     */
    private $failNextCall = false;

    /**
     * @var ConnectivityChecker
     */
    private $connectivityChecker;

    public function __construct(ConnectivityChecker $connectivityChecker)
    {
        $this->connectivityChecker = $connectivityChecker;
    }

    public function failNextCall(): void
    {
        $this->failNextCall = true;
    }

    /**
     * @inheritDoc
     */
    public function isConnected($connection): bool
    {
        if ($this->failNextCall) {
            $this->failNextCall = false;

            return false;
        }

        return $this->connectivityChecker->isConnected($connection);
    }
}
