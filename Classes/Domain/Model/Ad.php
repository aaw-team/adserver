<?php

declare(strict_types=1);
namespace AawTeam\Adserver\Domain\Model;

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

/**
 * Ad
 */
class Ad extends AbstractIdentifiableEntity
{
    /**
     * @var Code|null
     */
    protected $code;

    public function getCode(): ?Code
    {
        return $this->code;
    }

    public function setCode(?Code $code): void
    {
        $this->code = $code;
    }
}
