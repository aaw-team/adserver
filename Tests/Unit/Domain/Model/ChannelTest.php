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
use AawTeam\Adserver\Domain\Model\Channel;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * ChannelTest
 */
class ChannelTest extends UnitTestCase
{
    /**
     * @var Channel
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = new Channel();
        $this->subject->initializeObject();
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
        // Property: campaigns
        $campaigns = new ObjectStorage();
        self::assertSame(0, $this->subject->getCampaigns()->count());
        $this->subject->setCampaigns($campaigns);
        self::assertEquals($campaigns, $this->subject->getCampaigns());
    }
}
