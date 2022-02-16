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
use Psr\Http\Message\ServerRequestInterface;

/**
 * CorsRequestHandler
 *
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
 * @link https://fetch.spec.whatwg.org/#http-cors-protocol
 */
class CorsRequestHandler
{
    /**
     * @var ResponseFactoryInterface
     */
    protected $responseFactory;

    /**
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function isCorsRequest(ServerRequestInterface $request): bool
    {
        // CORS requests MUST present an 'origin' header
        return $request->hasHeader('origin');
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function isCorsPreflightRequest(ServerRequestInterface $request): bool
    {
        return  $this->isCorsRequest($request)
            && ($request->getMethod() === 'OPTIONS')
            && $request->hasHeader('Access-Control-Request-Method')
            && $request->hasHeader('Access-Control-Request-Headers');
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function createCorsResponse(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!$this->isCorsRequest($request) || $this->isCorsPreflightRequest($request)) {
            return $response;
        }

        return $response
            ->withHeader('Access-Control-Allow-Origin', (string)$request->getHeaderLine('origin'))
            ->withAddedHeader('Vary', 'Origin')
            ->withHeader('Access-Control-Allow-Credentials', 'true');
    }

    /**
     * Returns a response to a CORS preflight request. Or null when $request is
     * no CORS preflight request.
     *
     * @return ResponseInterface|null
     */
    public function createCorsPreflightResponse(ServerRequestInterface $request): ?ResponseInterface
    {
        if (!$this->isCorsPreflightRequest($request)) {
            return null;
        }

        return $this->responseFactory->createResponse(204)
        // Add debug info
        ->withHeader('X-Is-Preflight-Response', 'true')

        // Allow the origin (plus add "Origin" to the "Vary" header)
        ->withHeader('Access-Control-Allow-Origin', (string)$request->getHeaderLine('origin'))
        ->withAddedHeader('Vary', 'Origin')

        // Preflight cache lifetime in seconnds
        ->withHeader('Access-Control-Max-Age', '5')

        // The 'unbounded' list of methods (that might be used by the client)
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST')

        // The 'unbounded' list of headers (that might be used by the client)
        ->withHeader('Access-Control-Allow-Headers', 'Origin, Authorization, Bearer, Accept-Encoding, Referer, User-Agent');
    }
}
