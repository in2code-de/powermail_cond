<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3_MODE') || die();

/**
 * Add TypoScript Static Template
 */
ExtensionManagementUtility::addStaticFile(
    'powermail_cond',
    'Configuration/TypoScript/',
    'Main TypoScript'
);
