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
use AawTeam\Adserver\Domain\Model\Ad;
use AawTeam\Adserver\Domain\Model\Code;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * AdTest
 */
class AdTest extends UnitTestCase
{
    /**
     * @var Ad
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = new Ad();
    }

    /**
     * @test
     */
    public function isIdentifiableEntyty()
    {
        self::assertInstanceOf(AbstractIdentifiableEntity::class, $this->subject);
    }

    /**
     * @test
     */
    public function propertySettersAndGetters()
    {
        // Property: code
        $code = new Code();
        self::assertNull($this->subject->getCode());
        $this->subject->setCode($code);
        self::assertSame($code, $this->subject->getCode());
    }
}
