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
        'title' => 'LLL:EXT:adserver/Resources/Private/Language/db.xlf:tca.tx_adserver_domain_model_channel',
        'label' => 'title',
        'sortby' => 'sorting',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'hideAtCopy' => true,
        'prependAtCopy' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.prependAtCopy',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title',
        // @todo add a record icon
    ],
    'columns' => [
        'title' => [
            'label' => 'LLL:EXT:adserver/Resources/Private/Language/db.xlf:tca.tx_adserver_domain_model_channel.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim,required'
            ],
        ],
        'identifier' => [
            'label' => 'LLL:EXT:adserver/Resources/Private/Language/db.xlf:tca.tx_adserver_domain_model_channel.identifier',
            'config' => [
                'type' => 'slug',
                'generatorOptions' => [
                    'fields' => ['title'],
                    'prefixParentPageSlug' => true,
                    'replacements' => [
                        '/' => '',
                    ],
                ],
                'fallbackCharacter' => '-',
                'eval' => 'unique'
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:adserver/Resources/Private/Language/db.xlf:tca.tx_adserver_domain_model_channel.hidden',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'label' => 'LLL:EXT:adserver/Resources/Private/Language/db.xlf:tca.tx_adserver_domain_model_channel.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0
            ],
        ],
        'endtime' => [
            'label' => 'LLL:EXT:adserver/Resources/Private/Language/db.xlf:tca.tx_adserver_domain_model_channel.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
            ],
        ],
        'campaigns' => [
            'label' => 'LLL:EXT:adserver/Resources/Private/Language/db.xlf:tca.tx_adserver_domain_model_channel.campaigns',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_adserver_domain_model_campaign',
                'foreign_table_where' => 'ORDER BY tx_adserver_domain_model_campaign.sorting',
                'MM' => 'tx_adserver_domain_model_channel_to_campaign',
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                     title, identifier, campaigns,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
            ',
        ],
    ],
    'palettes' => [
        'hidden' => [
            'showitem' => '
                hidden;LLL:EXT:adserver/Resources/Private/Language/db.xlf:tca.tx_adserver_domain_model_channel.hidden.formlabel
            ',
        ],
        'access' => [
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access',
            'showitem' => '
                starttime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel,
                endtime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel,
            ',
        ],
    ],
];
