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

use AawTeam\Adserver\Domain\Repository\AdRepository;
use AawTeam\Adserver\Persistence\AbstractIdentifiableManagerRepository;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * AdRepositoryTest
 */
class AdRepositoryTest extends UnitTestCase
{
    /**
     * @test
     */
    public function isRepository()
    {
        self::assertContains(AbstractIdentifiableManagerRepository::class, class_parents(AdRepository::class));
    }
}
