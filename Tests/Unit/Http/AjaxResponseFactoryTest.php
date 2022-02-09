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

use AawTeam\Adserver\Http\AjaxResponse;
use AawTeam\Adserver\Http\AjaxResponseFactory;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use Psr\Http\Message\ResponseFactoryInterface;

/**
 * AjaxResponseFactoryTest
 */
class AjaxResponseFactoryTest extends UnitTestCase
{
    /**
     * @var AjaxResponseFactory
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = new AjaxResponseFactory();
    }

    /**
     * @test
     */
    public function isResponseFactoryInterface(): void
    {
        self::assertInstanceOf(ResponseFactoryInterface::class, $this->subject);
    }

    /**
     * @test
     */
    public function createsAjaxResponseInstance(): void
    {
        self::assertInstanceOf(AjaxResponse::class, $this->subject->createResponse());
    }

    /**
     * @test
     */
    public function addsContentTypeHttpHeader(): void
    {
        $response = $this->subject->createResponse();
        self::assertTrue($response->hasHeader('content-type'));
        self::assertSame('application/json; charset=utf-8', $response->getHeaderLine('content-type'));
    }
}
