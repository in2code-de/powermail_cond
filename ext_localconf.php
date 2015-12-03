<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

/**
 * Include Plugins
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'In2code.' . $_EXTKEY,
    'Pi1',
    [
        'Condition' => 'buildCondition'
    ],
    [
        'Condition' => 'buildCondition'
    ]
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['In2code\\Powermail\\Domain\\Validator\\InputValidator'] = [
    'className' => 'In2code\\PowermailCond\\Domain\\Validator\\ConditionAwareValidator',
];
