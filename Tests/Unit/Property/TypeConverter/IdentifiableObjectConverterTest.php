<?php

declare(strict_types=1);
namespace AawTeam\Adserver\Tests\Unit\Domain\Model;

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

use AawTeam\Adserver\Property\TypeConverter\IdentifiableObjectConverter;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Property\TypeConverter\ObjectConverter;
use TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;
use TYPO3\CMS\Extbase\Property\TypeConverterInterface;

/**
 * IdentifiableObjectConverterTest
 */
class IdentifiableObjectConverterTest extends UnitTestCase
{
    /**
     * @test
     */
    public function isTypeConverterInterface(): void
    {
        self::assertContains(TypeConverterInterface::class, class_implements(IdentifiableObjectConverter::class));
    }

    /**
     * @test
     */
    public function priorityIsHigherThanExtbasePersistentObjectConverter(): void
    {
        $persistentObjectConverter = new PersistentObjectConverter();
        $objectConverter = new ObjectConverter();
        $subject = new IdentifiableObjectConverter();
        self::assertGreaterThan($objectConverter->getPriority(), $subject->getPriority());
        self::assertLessThan($persistentObjectConverter->getPriority(), $subject->getPriority());
    }
}
