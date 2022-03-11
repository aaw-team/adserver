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

use AawTeam\Adserver\Api\DataFinder;
use AawTeam\Adserver\Domain\Model\Channel;
use AawTeam\Adserver\Domain\Repository\ChannelRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * ApiController
 */
class ApiController extends AbstractApiController
{
    /**
     * @var DataFinder
     */
    protected $dataFinder;

    /**
     * @var ChannelRepository
     */
    protected $channelRepository;

    public function __construct(DataFinder $dataFinder, ChannelRepository $channelRepository)
    {
        $this->dataFinder = $dataFinder;
        $this->channelRepository = $channelRepository;
    }

    /**
     * Public API method
     *
     * @return ResponseInterface
     */
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

    /**
     * Public API method
     *
     * Returns setup information for ad clients.
     *
     * @param string $channel
     * @return ResponseInterface
     */
    protected function setupAction(?string $channel = null): ResponseInterface
    {
        if ($channel === null) {
            return $this->processAndReturnResponse(
                $this->ajaxResponseFactory->createResponse(400)->withError(0, 'Missing required argument "channel"')
            );
        }
        /** @var Channel $channelObject */
        $channelObject = null;
        if (MathUtility::canBeInterpretedAsInteger($channel)) {
            if ($channel < 1) {
                return $this->processAndReturnResponse(
                    $this->ajaxResponseFactory->createResponse(400)->withError(0, 'Argument "channel" must be not empty string or positive integer')
                );
            }
            $channelObject = $this->channelRepository->findByIdentifier($channel);
        } elseif ($channel === '') {
            return $this->processAndReturnResponse(
                $this->ajaxResponseFactory->createResponse(400)->withError(0, 'Argument "channel" must be not empty string or positive integer')
            );
        } else {
            $channelObject = $this->channelRepository->findByIdentifiableString($channel);
        }

        // No active channel found (404)
        if (!$channelObject) {
            return $this->processAndReturnResponse(
                $this->ajaxResponseFactory->createResponse(404)->withError(0, 'Unknown channel')
            );
        }

        $setupData = $this->dataFinder->aggregateSetup($channelObject);
        return $this->processAndReturnResponse(
            $this->ajaxResponseFactory->createResponse()->withData($setupData)
        );
    }
}
