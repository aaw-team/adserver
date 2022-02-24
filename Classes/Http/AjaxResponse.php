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

use Psr\Http\Message\StreamInterface;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\Stream;

/**
 * AjaxResponse
 *
 * This class manages the server-side part of the AJAX communication.
 * Additionally we add a compatibility layer for TYPO3 < v11, where the
 * ActionController does not expect ResponseInterface yet (see __toString()).
 */
class AjaxResponse extends Response
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $notifications = [];

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * Overriding parent constructor to force in the contents of the stream
     *
     * @param string $body
     * @param number $statusCode
     * @param array $headers
     * @param string $reasonPhrase
     */
    public function __construct($statusCode = 200, $headers = [], string $reasonPhrase = '')
    {
        parent::__construct($this->createStream(), $statusCode, $headers, $reasonPhrase);
    }

    /**
     * Compatibility for TYPO3 < v11, where the ActionController does not expect
     * ResponseInterface yet
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->body;
    }

    /**
     * @param array $data
     * @return self
     */
    public function withData(array $data): self
    {
        $clonedObject = clone $this;
        $clonedObject->data = $data;
        return $clonedObject->withBody($clonedObject->createStream());
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string $notification
     * @return self
     */
    public function withNotification(string $notification): self
    {
        $clonedObject = clone $this;
        $clonedObject->notifications[] = $notification;
        return $clonedObject->withBody($clonedObject->createStream());
    }

    /**
     * @return array
     */
    public function getNotifications(): array
    {
        return $this->notifications;
    }

    /**
     * @param string $error
     * @return self
     */
    public function withError(int $code, string $message): self
    {
        $clonedObject = clone $this;
        $clonedObject->errors[] = [
            'code' => $code,
            'message' => $message,
        ];
        return $clonedObject->withBody($clonedObject->createStream());
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    private function createStream(): StreamInterface
    {
        $stream = new Stream('php://temp', 'r+');
        $stream->write($this->createEncodedJsonSkeleton());
        return $stream;
    }

    private function createEncodedJsonSkeleton(): string
    {
        $skeleton = [];
        // If there are errors, include only them
        if (!empty($this->errors)) {
            $skeleton['errors'] = $this->errors;
        } else {
            if (!empty($this->notifications)) {
                $skeleton['notifications'] = $this->notifications;
            }
            if (!empty($this->data)) {
                $skeleton['data'] = $this->data;
            }
        }

        return empty($skeleton) ? '{}' : json_encode($skeleton);
    }
}
