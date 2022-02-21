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

use AawTeam\Adserver\Domain\Model\AbstractIdentifiableEntity;
use AawTeam\Adserver\Domain\Model\Place;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * PlaceTest
 */
class PlaceTest extends UnitTestCase
{
    /**
     * @var Place
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = new Place();
    }

    /**
     * @test
     */
    public function isIdentifiableEntyty()
    {
        self::assertInstanceOf(AbstractIdentifiableEntity::class, $this->subject);
    }
}
