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
 * Campaign
 */
class Campaign extends AbstractIdentifiableEntity
{
    /**
     * @var ObjectStorage<Page>
     */
    protected $limitToPages;

    public function initializeObject()
    {
        $this->limitToPages = new ObjectStorage();
    }

    /**
     * @return ObjectStorage<Page>
     */
    public function getLimitToPages(): ObjectStorage
    {
        return $this->limitToPages;
    }

    /**
     * @param ObjectStorage<Page> $limitToPages
     */
    public function setLimitToPages(ObjectStorage $limitToPages): void
    {
        $this->limitToPages = $limitToPages;
    }
}
