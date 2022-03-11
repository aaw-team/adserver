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
use AawTeam\Adserver\Domain\Repository\PlacementRepository;

/**
 * PlacementFinder
 */
class PlacementFinder implements PlacementFinderInterface
{
    /**
     * @var PlacementRepository
     */
    protected $placementRepository;

    /**
     * @param PlacementRepository $placementRepository
     */
    public function __construct(PlacementRepository $placementRepository)
    {
        $this->placementRepository = $placementRepository;
    }

    /**
     * {@inheritDoc}
     * @see \AawTeam\Adserver\Api\DataAggregation\PlacementFinderInterface::findByChannel()
     */
    public function findByChannel(Channel $channel): \Traversable
    {
        $campaignUids = [];
        foreach ($channel->getCampaigns() as $campaign) {
            $campaignUids[] = $campaign->getUid();
        }

        $qb = $this->placementRepository->createQueryBuilderForTable();
        $qb->select('placement.*')
        ->from('tx_adserver_domain_model_placement', 'placement')
        ->groupBy('placement.place', 'placement.uid')
        ->where(
            $qb->expr()->in('placement.campaign', $campaignUids)
        );
        $query = $this->placementRepository->createQuery();
        $query->statement($qb);
        return $query->execute();
    }
}
