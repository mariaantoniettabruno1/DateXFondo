<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 25-October-2021 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GravityKit\GravityExport\Spatie\Dropbox\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;

class BadRequest extends Exception
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    public $response;

    /**
     * The dropbox error code supplied in the response.
     *
     * @var string|null
     */
    public $dropboxCode;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;

        $body = json_decode($response->getBody(), true);

        if ($body !== null) {
            if (isset($body['error']['.tag'])) {
                $this->dropboxCode = $body['error']['.tag'];
            }

            parent::__construct($body['error_summary']);
        }
    }
}
