<?php

declare(strict_types=1);
namespace AawTeam\Adserver\Persistence;

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

use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * IdentifiableManagerInterface
 */
interface IdentifiableManagerInterface
{
    /**
     * @param string $identifier
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array
     */
    public function findByIdentifiableString(string $identifier);

    /**
     * @param string $identifier
     * @return int
     */
    public function countByIdentifiableString(string $identifier): int;

    /**
     * @param string $identifier
     * @return QueryInterface
     */
    public function createQueryFilteringByIdentifiableString(string $identifier): QueryInterface;
}
