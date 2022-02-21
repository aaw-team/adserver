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

use AawTeam\Adserver\Persistence\AbstractIdentifiableManagerRepository;
use AawTeam\Adserver\Persistence\IdentifiableManagerInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * AbstractIdentifiableManagerRepositoryTest
 */
class AbstractIdentifiableManagerRepositoryTest extends UnitTestCase
{
    /**
     * @test
     */
    public function isIdentifiableManager()
    {
        self::assertContains(IdentifiableManagerInterface::class, class_implements(AbstractIdentifiableManagerRepository::class));
    }
}
