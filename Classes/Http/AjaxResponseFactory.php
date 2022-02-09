<?php

declare(strict_types=1);
namespace AawTeam\Adserver\Http;

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

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * AjaxResponseFactory
 */
class AjaxResponseFactory implements ResponseFactoryInterface
{
    /**
     * {@inheritDoc}
     * @see \Psr\Http\Message\ResponseFactoryInterface::createResponse()
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return (new AjaxResponse($code, [], $reasonPhrase))
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withHeader('X-Adserver', '1')
        ;
    }
}
