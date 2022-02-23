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
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * AbstractIdentifiableManagerRepository
 */
abstract class AbstractIdentifiableManagerRepository extends Repository implements IdentifiableManagerInterface
{
    public function findByIdentifiableString(string $identifier, bool $returnRawQueryResult = false)
    {
        $result = $this->createQueryFilteringByIdentifiableString($identifier)->execute($returnRawQueryResult);
        if ($result instanceof QueryResultInterface) {
            return $result->getFirst();
        }
        if (is_array($result)) {
            return $result[0] ?? null;
        }
        return null;
    }

    public function countByIdentifiableString(string $identifier): int
    {
        return $this->createQueryFilteringByIdentifiableString($identifier)->execute()->count();
    }

    public function createQueryFilteringByIdentifiableString(string $identifier): QueryInterface
    {
        $query = $this->createQuery();
        return $query->matching(
            $query->equals('identifier', $identifier)
        );
    }
}
