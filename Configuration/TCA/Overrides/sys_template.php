<?php
defined('TYPO3_MODE') || die();

/**
 * Add TypoScript Static Template
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'powermail_cond',
    'Configuration/TypoScript/',
    'Main TypoScript'
);
