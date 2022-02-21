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

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Channel
 */
class Channel extends AbstractIdentifiableEntity
{
    /**
     * @var ObjectStorage<Campaign>
     */
    protected $campaigns;

    public function initializeObject()
    {
        $this->campaigns = new ObjectStorage();
    }

    /**
     * @return ObjectStorage<Campaign>
     */
    public function getCampaigns(): ObjectStorage
    {
        return $this->campaigns;
    }

    /**
     * @param ObjectStorage<Campaign> $campaigns
     */
    public function setCampaigns(ObjectStorage $campaigns): void
    {
        $this->campaigns = $campaigns;
    }
}
