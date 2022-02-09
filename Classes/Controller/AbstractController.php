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

use AawTeam\Adserver\Http\AjaxResponseFactory;
use AawTeam\Adserver\Mvc\LegacyResponseHandler;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * AbstractController
 */
abstract class AbstractController extends ActionController
{
    /**
     * @var AjaxResponseFactory
     */
    protected $ajaxResponseFactory;

    public function injectAjaxResponseFactory(AjaxResponseFactory $ajaxResponseFactory): void
    {
        $this->ajaxResponseFactory = $ajaxResponseFactory;
    }

    public function injectLegacyResponseHandler(LegacyResponseHandler $legacyResponseHandler): void
    {
        $this->legacyResponseHandler = $legacyResponseHandler;
    }

    /**
     * Helper method for TYPO3 < v11
     *
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    protected function legacyProcessAndReturnResponse(ResponseInterface $response): ResponseInterface
    {
        if (property_exists($this, 'response') && $this->response instanceof \TYPO3\CMS\Extbase\Mvc\Response) {
            (new LegacyResponseHandler())->handle($response, $this->response);
        }
        return $response;
    }
}
