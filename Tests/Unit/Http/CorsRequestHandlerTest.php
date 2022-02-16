<?php

declare(strict_types=1);
namespace AawTeam\Adserver\Tests\Unit\Http;

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

use AawTeam\Adserver\Http\CorsRequestHandler;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\ResponseFactory;
use TYPO3\CMS\Core\Http\ServerRequest;

/**
 * CorsRequestHandlerTest
 */
class CorsRequestHandlerTest extends UnitTestCase
{
    /**
     * Returns a correct CORS request
     *
     * @return ServerRequestInterface
     */
    protected function createCorsRequest(): ServerRequestInterface
    {
        return (new ServerRequest())
            ->withMethod('GET')
            ->withHeader('Origin', 'https://example.org');
    }

    /**
     * Returns a correct CORS preflight request
     *
     * @return ServerRequestInterface
     */
    protected function createCorsPreflightRequest(): ServerRequestInterface
    {
        return (new ServerRequest())
            ->withMethod('OPTIONS')
            ->withHeader('Access-Control-Request-Method', 'GET')
            ->withHeader('Access-Control-Request-Headers', 'origin, x-requested-with')
            ->withHeader('Origin', 'https://example.org');
    }

    /**
     * @test
     */
    public function analyzeIsCorsRequest(): void
    {
        $subject = new CorsRequestHandler(new ResponseFactory());

        $request = $this->createCorsRequest();

        // The complete and correct request
        self::assertTrue($subject->isCorsRequest($request), 'Complete and correct CORS request');

        // Test 'Origin' HTTP header
        $originalOriginHeader = $request->getHeaderLine('Origin');
        $request = $request->withoutHeader('Origin');
        self::assertFalse($subject->isCorsPreflightRequest($request), 'No "Origin" header');
        // Restore
        $request = $request->withHeader('Origin', $originalOriginHeader);
        self::assertTrue($subject->isCorsRequest($request), 'Complete and correct CORS request (after "Origin" header tests)');
    }

    /**
     * @test
     */
    public function analyzeIsCorsPreflightRequest(): void
    {
        $responseFactory = new ResponseFactory();
        $subject = new CorsRequestHandler($responseFactory);

        $preflightRequest = $this->createCorsPreflightRequest();

        // The complete and correct request
        self::assertTrue($subject->isCorsPreflightRequest($preflightRequest), 'Complete and correct CORS preflight request');

        // Test HTTP methods
        $originalMethod = $preflightRequest->getMethod();
        $preflightRequest = $preflightRequest->withMethod('OPTIONs');
        self::assertFalse($subject->isCorsPreflightRequest($preflightRequest), 'HTTP method is "OPTIONs" (some lowercase)');
        $preflightRequest = $preflightRequest->withMethod('options');
        self::assertFalse($subject->isCorsPreflightRequest($preflightRequest), 'HTTP method is "options" (all lowercase)');
        $preflightRequest = $preflightRequest->withMethod('GET');
        self::assertFalse($subject->isCorsPreflightRequest($preflightRequest), 'HTTP method is GET');
        $preflightRequest = $preflightRequest->withMethod('HEAD');
        self::assertFalse($subject->isCorsPreflightRequest($preflightRequest), 'HTTP method is HEAD');
        $preflightRequest = $preflightRequest->withMethod('POST');
        self::assertFalse($subject->isCorsPreflightRequest($preflightRequest), 'HTTP method is POST');
        $preflightRequest = $preflightRequest->withMethod('PUT');
        self::assertFalse($subject->isCorsPreflightRequest($preflightRequest), 'HTTP method is PUT');
        $preflightRequest = $preflightRequest->withMethod('DELETE');
        self::assertFalse($subject->isCorsPreflightRequest($preflightRequest), 'HTTP method is DELETE');
        $preflightRequest = $preflightRequest->withMethod('CONNECT');
        self::assertFalse($subject->isCorsPreflightRequest($preflightRequest), 'HTTP method is CONNECT');
        $preflightRequest = $preflightRequest->withMethod('TRACE');
        self::assertFalse($subject->isCorsPreflightRequest($preflightRequest), 'HTTP method is TRACE');
        $preflightRequest = $preflightRequest->withMethod('PATCH');
        self::assertFalse($subject->isCorsPreflightRequest($preflightRequest), 'HTTP method is PATCH');
        $preflightRequest = $preflightRequest->withMethod('');
        self::assertFalse($subject->isCorsPreflightRequest($preflightRequest), 'HTTP method is an empty string');
        $preflightRequest = $preflightRequest->withMethod('Arbitrary string');
        self::assertFalse($subject->isCorsPreflightRequest($preflightRequest), 'HTTP method is "Arbitrary string"');
        // Restore
        $preflightRequest = $preflightRequest->withMethod($originalMethod);
        self::assertTrue($subject->isCorsPreflightRequest($preflightRequest), 'Complete and correct CORS preflight request (after HTTP method tests)');

        // Test 'origin' HTTP header
        // Note: the presence of the "Origin" header is already tested in isCorsRequest(). Nevertheless we'll leave this test here, as it is requred for every CORS request
        $originalOriginHeader = $preflightRequest->getHeaderLine('origin');
        $preflightRequest = $preflightRequest->withoutHeader('origin');
        self::assertFalse($subject->isCorsPreflightRequest($preflightRequest), 'No "origin" header');
        // Restore
        $preflightRequest = $preflightRequest->withHeader('origin', $originalOriginHeader);
        self::assertTrue($subject->isCorsPreflightRequest($preflightRequest), 'Complete and correct CORS preflight request (after "origin" header tests)');

        // Test 'Access-Control-Request-Method' HTTP header
        $originalACRMHeader = $preflightRequest->getHeaderLine('Access-Control-Request-Method');
        $preflightRequest = $preflightRequest->withoutHeader('Access-Control-Request-Method');
        self::assertFalse($subject->isCorsPreflightRequest($preflightRequest), 'No "Access-Control-Request-Method" header');
        // Restore
        $preflightRequest = $preflightRequest->withHeader('Access-Control-Request-Method', $originalACRMHeader);
        self::assertTrue($subject->isCorsPreflightRequest($preflightRequest), 'Complete and correct CORS preflight request (after "Access-Control-Request-Method" header tests)');

        // Test 'Access-Control-Request-Headers' HTTP header
        $originalACRHHeader = $preflightRequest->getHeaderLine('Access-Control-Request-Headers');
        $preflightRequest = $preflightRequest->withoutHeader('Access-Control-Request-Headers');
        self::assertFalse($subject->isCorsPreflightRequest($preflightRequest), 'No "Access-Control-Request-Headers" header');
        // Restore
        $preflightRequest = $preflightRequest->withHeader('Access-Control-Request-Headers', $originalACRHHeader);
        self::assertTrue($subject->isCorsPreflightRequest($preflightRequest), 'Complete and correct CORS preflight request (after "Access-Control-Request-Headers" header tests)');
    }

    /**
     * @test
     */
    public function analyzeCorsResponse()
    {
        $subject = new CorsRequestHandler(new ResponseFactory());

        $request = $this->createCorsRequest();
        $origin = $request->getHeaderLine('Origin');
        $response = $subject->createCorsResponse($request, (new ResponseFactory())->createResponse());

        self::assertTrue($response->hasHeader('Access-Control-Allow-Origin'));
        self::assertSame($origin, $response->getHeaderLine('Access-Control-Allow-Origin'));

        self::assertTrue($response->hasHeader('Vary'));
        self::assertGreaterThanOrEqual(0, strpos($response->getHeaderLine('Vary'), 'Origin'));

        self::assertTrue($response->hasHeader('Access-Control-Allow-Credentials'));
        self::assertSame('true', $response->getHeaderLine('Access-Control-Allow-Credentials'));

        // Cors preflights must not be touched by createCorsResponse()
        $preflightRequest = $this->createCorsPreflightRequest();
        $preflightResponseBefore = $subject->createCorsPreflightResponse($preflightRequest);
        $preflightResponseAfter = $subject->createCorsResponse($preflightRequest, $preflightResponseBefore);
        self::assertSame($preflightResponseBefore, $preflightResponseAfter);
    }

    /**
     * @test
     */
    public function analyzeCorsPreflightResponse()
    {
        $subject = new CorsRequestHandler(new ResponseFactory());
        $request = $this->createCorsPreflightRequest();
        $origin = $request->getHeaderLine('Origin');
        $response = $subject->createCorsPreflightResponse($request);

        self::assertTrue($response->hasHeader('Access-Control-Allow-Origin'));
        self::assertSame($origin, $response->getHeaderLine('Access-Control-Allow-Origin'));

        self::assertTrue($response->hasHeader('Vary'));
        self::assertGreaterThanOrEqual(0, strpos($response->getHeaderLine('Vary'), 'Origin'));

        self::assertTrue($response->hasHeader('Access-Control-Max-Age'));
        self::assertGreaterThanOrEqual(0, $response->getHeaderLine('Access-Control-Max-Age'));

        self::assertTrue($response->hasHeader('Access-Control-Allow-Methods'));
        self::assertSame('GET, POST', $response->getHeaderLine('Access-Control-Allow-Methods'));

        self::assertTrue($response->hasHeader('Access-Control-Allow-Headers'));
        self::assertSame('Origin, Authorization, Bearer, Accept-Encoding, Referer, User-Agent', $response->getHeaderLine('Access-Control-Allow-Headers'));

        // "normal" cors (but not preflight) requests must not be touched by createCorsPreflightResponse()
        self::assertNull($subject->createCorsPreflightResponse($this->createCorsRequest()));
    }
}
