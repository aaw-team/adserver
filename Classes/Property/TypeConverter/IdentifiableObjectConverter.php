<?php

declare(strict_types=1);
namespace AawTeam\Adserver\Property\TypeConverter;

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

use AawTeam\Adserver\Domain\Model\AbstractIdentifiableEntity;
use AawTeam\Adserver\Domain\Model\IdentifiableEntityInterface;
use TYPO3\CMS\Core\Utility\ClassNamingUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface;
use TYPO3\CMS\Extbase\Property\TypeConverter\AbstractTypeConverter;

/**
 * IdentifiableObjectConverter
 */
class IdentifiableObjectConverter extends AbstractTypeConverter
{
    /**
     * @var string[]
     */
    protected $sourceTypes = [
        'string'
    ];

    /**
     * @var string
     */
    protected $targetType = AbstractIdentifiableEntity::class;

    /**
     * Choose a priority higher than
     * \TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter but
     * lower than \TYPO3\CMS\Extbase\Property\TypeConverter\ObjectConverter
     *
     * @var int
     */
    protected $priority = 15;

    /**
     * {@inheritDoc}
     * @see \TYPO3\CMS\Extbase\Property\TypeConverter\AbstractTypeConverter::canConvertFrom()
     */
    public function canConvertFrom($source, string $targetType): bool
    {
        return is_string($source)
            && class_exists($targetType)
            && in_array(IdentifiableEntityInterface::class, class_implements($targetType))
        ;
    }

    /**
     * {@inheritDoc}
     * @see \TYPO3\CMS\Extbase\Property\TypeConverterInterface::convertFrom()
     */
    public function convertFrom($source, string $targetType, array $convertedChildProperties = [], PropertyMappingConfigurationInterface $configuration = null)
    {
        $repositoryClassName = ClassNamingUtility::translateModelNameToRepositoryName($targetType);
        $repository = GeneralUtility::makeInstance(ObjectManager::class)->get($repositoryClassName);
        $result = $repository->findByIdentifiableString($source);
        return $result ?? $source;
    }
}
