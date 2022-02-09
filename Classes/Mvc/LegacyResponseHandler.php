<?php

declare(strict_types=1);
namespace AawTeam\Adserver\Mvc;

/*
 * Copyright by Agentur am Wasser | Maeder & Partner AG
 *
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Response as ExtbaseResponse;

/**
 * LegacyResponseHandler
 */
class LegacyResponseHandler
{
    /**
     * Register any needed data from the ResponseInterface in the extbase
     * response object. Note: the message body can be ignored (see
     * \AawTeam\Adserver\Http\AjaxResponse::__toString())
     *
     * @param ResponseInterface $response
     * @param ExtbaseResponse $extbaseResponse
     */
    public function handle(ResponseInterface $response, ExtbaseResponse $extbaseResponse): void
    {
        // HTTP status code and reason phrase
        $extbaseResponse->setStatus($response->getStatusCode(), $response->getReasonPhrase());

        // HTTP headers
        foreach (array_keys($response->getHeaders()) as $name) {
            $extbaseResponse->setHeader($name, $response->getHeaderLine($name));
        }
    }
}
