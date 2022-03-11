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

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * AbstractRepository
 */
abstract class AbstractRepository extends Repository
{
    /**
     * @var DataMapper
     */
    protected $dataMapper;

    public function injectDataMapper(DataMapper $dataMapper)
    {
        $this->dataMapper = $dataMapper;
    }

    public function createQueryBuilderForTable(string $tableName = null): QueryBuilder
    {
        return $this->getConnectionForTable($tableName)->createQueryBuilder();
    }

    public function getConnectionForTable(string $tableName = null): Connection
    {
        if ($tableName === null) {
            $tableName = $this->getTableName();
        }
        return GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($tableName);
    }

    protected function getTableName(): string
    {
        return $this->dataMapper->getDataMap($this->objectType)->getTableName();
    }
}
