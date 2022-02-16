<?php

declare(strict_types=1);
namespace AawTeam\Adserver\Controller;

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

/**
 * ApiController
 */
class ApiController extends AbstractApiController
{
    protected function indexAction(): ResponseInterface
    {
        $data = [
            'api-endpoint-uri' => $this->uriBuilder->reset()->setTargetPageType((int)$this->settings['adserverPageType'])->setCreateAbsoluteUri(true)->build(),
        ];
        $response = $this->ajaxResponseFactory->createResponse()
            ->withData($data)
            ->withNotification('Welcome, this is adserver speaking, powered by TYPO3');

        return $this->processAndReturnResponse($response);
    }
}
