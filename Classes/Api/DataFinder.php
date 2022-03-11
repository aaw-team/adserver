<?php

declare(strict_types=1);
namespace AawTeam\Adserver\Api;

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

use AawTeam\Adserver\Domain\Model\Channel;
use AawTeam\Adserver\Domain\Model\Placement;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;

/**
 * DataFinder
 */
class DataFinder
{
    /**
     * @var PlacementFinderInterface
     */
    protected $placementFinder;

    /**
     * @var FrontendInterface
     */
    protected $cache;

    public function __construct(PlacementFinderInterface $placementFinder, FrontendInterface $cache)
    {
        $this->placementFinder = $placementFinder;
        $this->cache = $cache;
    }

    /**
     * @param Channel $channel
     * @return array
     */
    public function aggregateSetup(Channel $channel): array
    {
        // No active campaigns: return an empty response
        if ($channel->getCampaigns()->count() < 1) {
            return [];
        }

        $cacheKey = 'channel-' . $channel->getUid();
        if (($setupData = $this->cache->get($cacheKey)) === false) {

            // Find placement candidates
            $placementCandidates = $this->placementFinder->findByChannel($channel);
            $placementCandidatesByPlace = [];
            foreach ($placementCandidates as $placementCandidate) {
                $placementCandidatesByPlace[$placementCandidate->getPlace()->getIdentifier()][] = $placementCandidate;
            }

            // Prioritize placement candidates
            foreach ($placementCandidatesByPlace as $placeIdentifier => $placementCandidates) {
                uasort($placementCandidates, function (Placement $a, Placement $b): int {
                    if (!$a->getCampaign()->_hasProperty('priority') || !$b->getCampaign()->_hasProperty('priority')) {
                        return $a->getCampaign()->getIdentifier() <=> $b->getCampaign()->getIdentifier();
                    }
                    return $a->getCampaign()->_getProperty('priority') <=> $b->getCampaign()->_getProperty('priority');
                });
                $placementCandidatesByPlace[$placeIdentifier] = $placementCandidates;
            }

            // Get rid of the unneeded placement candidates
            foreach ($placementCandidatesByPlace as $placeIdentifier => $placementCandidates) {
                $placementCandidatesByPlace[$placeIdentifier] = array_shift($placementCandidates);
            }

            // Prepare and format the setup data
            $setupData = [
                'placements' => [],
            ];
            foreach ($placementCandidatesByPlace as $placeIdentifier => $placementCandidate) {
                /** @var Placement $placementCandidate */
                $setupData['placements'][] = [
                    'ad' => [
                        'title' => $placementCandidate->getAd()->getTitle(),
                        'identifier' => $placementCandidate->getAd()->getIdentifier(),
                    ],
                    'campaign' => [
                        'title' => $placementCandidate->getCampaign()->getTitle(),
                        'identifier' => $placementCandidate->getCampaign()->getIdentifier(),
                    ],
                    'place' => [
                        'title' => $placementCandidate->getPlace()->getTitle(),
                        'identifier' => $placementCandidate->getPlace()->getIdentifier(),
                    ],
                    'endpoints' => [
                        // @todo: define endpoints here? Does the controller add the endpoint URIs?
                        'iframe' => 'https://example.org/ads/iframe',
                        'track-load' => 'https://example.org/api/track/load',
                        'track-impression' => 'https://example.org/api/track/impression',
                        'track-click' => 'https://example.org/api/track/click',
                    ],
                ];
            }
            $this->cache->set($cacheKey, $setupData);
        }

        return $setupData;
    }
}
