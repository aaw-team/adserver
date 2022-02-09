<?php

declare(strict_types=1);
namespace AawTeam\Adserver\Tests\Unit\Mvc;

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

use AawTeam\Adserver\Mvc\LegacyResponseHandler;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Http\ResponseFactory;
use TYPO3\CMS\Extbase\Mvc\Response as ExtbaseResponse;

/**
 * LegacyResponseHandlerTest
 */
class LegacyResponseHandlerTest extends UnitTestCase
{
    /**
     * @var LegacyResponseHandler
     */
    protected $subject;

    protected function setUp(): void
    {
        if (!class_exists(ExtbaseResponse::class)) {
            self::markTestSkipped(
                'The extbase-internal response class does not exist anymore, thus there is no need for testing the legacy behaviour'
            );
        }
        $this->subject = new LegacyResponseHandler();
    }

    /**
     * @test
     */
    public function correctlyRegistersHttpHeadersInExtbaseResponse(): void
    {
        $response = (new ResponseFactory())->createResponse()
            ->withHeader('X-Test-1', 'A test value')
            ->withHeader('X-Test-2', 'Some other test value')
            ->withAddedHeader('X-Test-2', 'A third value');
        $extbaseResponse = new ExtbaseResponse();

        $this->subject->handle($response, $extbaseResponse);
        $actualHeaders = $extbaseResponse->getUnpreparedHeaders();

        // A simple header
        self::assertArrayHasKey('X-Test-1', $actualHeaders);
        self::assertSame('A test value', $actualHeaders['X-Test-1'][0]);

        // A 'multi-part' header
        self::assertArrayHasKey('X-Test-2', $actualHeaders);
        self::assertSame('Some other test value,A third value', $actualHeaders['X-Test-2'][0]);
    }

    /**
     * @test
     * @dataProvider correctlyRegisterHttpStatusDataProvider
     */
    public function correctlyRegisterHttpStatus(int $status, ?string $reasonPhrase): void
    {
        $response = (new ResponseFactory())->createResponse($status, $reasonPhrase);
        $extbaseResponse = new ExtbaseResponse();
        $this->subject->handle($response, $extbaseResponse);

        self::assertSame($status, $extbaseResponse->getStatusCode());

        // Workaround the fact, that a PSR ResponseInterface MAY choose to return the recommended default, see ResponseInterface::getReasonPhrase()
        $completeStatusVariants = [
            $status . ' ' . $reasonPhrase,
            $status . ' ' . $response->getReasonPhrase(),
        ];
        self::assertContains($extbaseResponse->getStatus(), $completeStatusVariants);
    }

    public function correctlyRegisterHttpStatusDataProvider(): array
    {
        return [
            [200, ''],
            [200, 'OK'],
            [200, 'Something else'],
            [307, ''],
            [307, 'Temporary Redirect'],
            [307, 'Now for something completely different'],
        ];
    }
}
