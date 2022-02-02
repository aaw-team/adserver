<?php

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

return [
    'ctrl' => [
        'title' => 'placement',
        'hideTable' => true,
        'label' => 'uid',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
    ],
    'columns' => [
        'ad' => [
            'label' => 'ad',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_adserver_domain_model_ad',
            ],
        ],
        'campaign' => [
            'label' => 'campaign',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_adserver_domain_model_campaign',
            ],
        ],
        'place' => [
            'label' => 'place',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_adserver_domain_model_place',
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                 ad, campaign, place,
            ',
        ],
    ],
    'palettes' => [],
];
