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
use AawTeam\Adserver\Domain\Model\Code;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * CodeTest
 */
class CodeTest extends UnitTestCase
{
    /**
     * @var Code
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = new Code();
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
        // Property: type
        $type = 2;
        self::assertSame(0, $this->subject->getType());
        $this->subject->setType($type);
        self::assertSame($type, $this->subject->getType());

        // Property: source
        $source = 'var a = "hello world";';
        self::assertNull($this->subject->getSource());
        $this->subject->setSource($source);
        self::assertSame($source, $this->subject->getSource());
    }
}
