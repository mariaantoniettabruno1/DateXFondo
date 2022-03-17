<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 25-October-2021 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GravityKit\GravityExport\Spatie\Dropbox;

interface TokenProvider
{
    public function getToken(): string;
}
