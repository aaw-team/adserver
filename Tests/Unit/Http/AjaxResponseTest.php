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
use Nimut\TestingFramework\TestCase\UnitTestCase;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\StreamFactory;

/**
 * AjaxResponseTest
 */
class AjaxResponseTest extends UnitTestCase
{
    /**
     * @var AjaxResponse
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = new AjaxResponse();
    }

    /**
     * @test
     */
    public function isResponseInterface(): void
    {
        self::assertInstanceOf(ResponseInterface::class, $this->subject);
    }

    /**
     * @test
     */
    public function dataIsSet(): void
    {
        $data = ['answer' => 42, 'question' => 'unknown'];
        self::assertSame($data, $this->subject->withData($data)->getData());

        $data = ['answer' => 'yes', 'question' => 'Is $data replaced?'];
        self::assertSame($data, $this->subject->withData($data)->getData());
    }

    /**
     * @test
     */
    public function notificationsAreSet(): void
    {
        $notifications = [0 => 'Hello world'];
        $subject = $this->subject->withNotification($notifications[0]);
        self::assertSame($notifications, $subject->getNotifications());

        $notifications[1] = 'Can you hear me?';
        $subject = $subject->withNotification($notifications[1]);
        self::assertSame($notifications, $subject->getNotifications());
    }

    /**
     * @test
     */
    public function errorsAreSet(): void
    {
        $errors = [
            ['code' => 0, 'message' => 'Error message 1'],
        ];
        $subject = $this->subject->withError($errors[0]['code'], $errors[0]['message']);
        self::assertSame($errors, $subject->getErrors());

        $errors[1] = ['code' => 2, 'message' => 'Error message 2'];
        $subject = $subject->withError($errors[1]['code'], $errors[1]['message']);
        self::assertSame($errors, $subject->getErrors());
    }

    /**
     * @test
     */
    public function responseBodyContainsValidJson(): void
    {
        $responseBody = (string)$this->subject->getBody();
        self::assertJson($responseBody);
    }

    /**
     * @param array $data
     * @param array $notifications
     * @param array $errors
     * @param array $expectedStructure
     * @test
     * @dataProvider responseBodyContainsNeededStructureAndDataDataProvider
     * @depends responseBodyContainsValidJson
     */
    public function responseBodyContainsNeededStructureAndData(?array $data, ?array $notifications, ?array $errors, array $expectedStructure): void
    {
        $subject = $this->subject;

        if (is_array($data)) {
            $subject = $subject->withData($data);
        }
        if (is_array($notifications)) {
            foreach ($notifications as $notification) {
                $subject = $subject->withNotification($notification);
            }
        }
        if (is_array($errors)) {
            foreach ($errors as $error) {
                $subject = $subject->withError($error[0], $error[1]);
            }
        }
        $decodedResponseBody = json_decode((string)$subject->getBody(), true);
        self::assertSame($expectedStructure, $decodedResponseBody);
    }

    public function responseBodyContainsNeededStructureAndDataDataProvider(): array
    {
        return [
            'empty-skeleton' => [
                null,
                null,
                null,
                [
                    'notifications' => [],
                    'data' => [],
                ]
            ],
            'only-data' => [
                ['answer' => 42],
                null,
                null,
                [
                    'notifications' => [],
                    'data' => ['answer' => 42],
                ]
            ],
            'only-notification' => [
                null,
                ['Hello world!'],
                null,
                [
                    'notifications' => ['Hello world!'],
                    'data' => [],
                ]
            ],
            'data-and-notification' => [
                ['answer' => 42],
                ['Hello world!'],
                null,
                [
                    'notifications' => ['Hello world!'],
                    'data' => ['answer' => 42],
                ]
            ],
            'empty-with-error' => [
                null,
                null,
                [[0 => 1, 1 => 'Error message']],
                [
                    'notifications' => [],
                    'data' => [],
                    'errors' => [
                        [
                            'code' => 1,
                            'message' => 'Error message',
                        ]
                    ],
                ]
            ],
        ];
    }

    /**
     * @test
     * @note: this test can be removed as soon as support for TYPO3 < v11 is dropped
     */
    public function toStringMethodReturnsContentsOfMessageBody(): void
    {
        $contentString = 'Response Body Content';
        $subject = $this->subject->withBody((new StreamFactory())->createStream($contentString));
        self::assertSame($contentString, (string)$subject);
    }
}
