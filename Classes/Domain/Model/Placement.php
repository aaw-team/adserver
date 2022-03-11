<?php

declare(strict_types=1);
namespace AawTeam\Adserver\Domain\Model;

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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Placement
 */
class Placement extends AbstractEntity
{
    /**
     * @var Ad
     */
    protected $ad;

    /**
     * @var Campaign
     */
    protected $campaign;

    /**
     * @var Place
     */
    protected $place;

    public function getAd(): Ad
    {
        return $this->ad;
    }

    public function setAd(Ad $ad)
    {
        $this->ad = $ad;
    }

    public function getCampaign(): Campaign
    {
        return $this->campaign;
    }

    public function setCampaign(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function getPlace(): Place
    {
        return $this->place;
    }

    public function setPlace(Place $place)
    {
        $this->place = $place;
    }
}
