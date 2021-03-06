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

defined('TYPO3') or defined('TYPO3_MODE') or die();

$controllerActions = [
    \AawTeam\Adserver\Controller\ApiController::class => 'index, setup',
];
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Adserver',
    'adserver',
    $controllerActions,
    $controllerActions
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['adserver_setup'] = [
    'backend' => \TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class,
    'frontend' => \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
];
