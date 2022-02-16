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
use AawTeam\Adserver\Http\CorsRequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\ImmediateResponseException;
use TYPO3\CMS\Core\Http\PropagateResponseException;

/**
 * AbstractApiController
 */
abstract class AbstractApiController extends AbstractController
{
    /**
     * @var AjaxResponseFactory
     */
    protected $ajaxResponseFactory;

    /**
     * @var CorsRequestHandler
     */
    protected $corsRequestHandler;

    /**
     * @param AjaxResponseFactory $ajaxResponseFactory
     */
    public function injectAjaxResponseFactory(AjaxResponseFactory $ajaxResponseFactory): void
    {
        $this->ajaxResponseFactory = $ajaxResponseFactory;
    }

    /**
     * @param CorsRequestHandler $corsRequestHandler
     */
    public function injectCorsRequestHandler(CorsRequestHandler $corsRequestHandler): void
    {
        $this->corsRequestHandler = $corsRequestHandler;
    }

    /**
     * {@inheritDoc}
     * @see \TYPO3\CMS\Extbase\Mvc\Controller\ActionController::initializeAction()
     */
    final public function initializeAction()
    {
        $request = $this->getTypo3Request();
        if ($this->corsRequestHandler->isCorsPreflightRequest($request)) {
            $preflightResponse = $this->corsRequestHandler->createCorsPreflightResponse($request);
            $preflightResponse = $this->processAndReturnResponse($preflightResponse, $request);

            // Because we cannot early return a Response in an ActionController, we need to throw them in
            // the available manner. This solution ist not very nice, but at this point, we have no alternative.
            // @see https://review.typo3.org/c/Packages/TYPO3.CMS/+/57866
            // @see https://review.typo3.org/c/Packages/TYPO3.CMS/+/67042
            if (class_exists(PropagateResponseException::class)) {
                $exception = new PropagateResponseException($preflightResponse);
            } else {
                $exception = new ImmediateResponseException($preflightResponse);
            }
            throw $exception;
        }
    }

    /**
     * @param ResponseInterface $response
     * @param ServerRequestInterface|null $request
     * @return ResponseInterface
     */
    protected function processAndReturnResponse(ResponseInterface $response, ?ServerRequestInterface $request = null): ResponseInterface
    {
        $request = $request ?? $this->getTypo3Request();

        if ($this->corsRequestHandler->isCorsRequest($request)) {
            $response = $this->corsRequestHandler->createCorsResponse($request, $response);
        }
        return parent::processAndReturnResponse($response, $request);
    }
}
