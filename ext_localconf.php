<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
if (!defined('TYPO3')) {
    die('Access denied.');
}

(static function () {
    /**
     * ContentElementWizard for Pi1
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        "@import 'EXT:powermail_cond/Configuration/TSConfig/WebList.typoscript'"
    );

    /**
     * Include Plugins
     */
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'powermail_cond',
        'Pi1',
        [
            \In2code\PowermailCond\Controller\ConditionController::class => 'buildCondition',
        ],
        [
            \In2code\PowermailCond\Controller\ConditionController::class => 'buildCondition',
        ]
    );

    /**
     * User field registrations in TCA/FlexForm
     */
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1588153418] = [
        'nodeName' => 'powermailCondShowNote',
        'priority' => 50,
        'class' => \In2code\PowermailCond\Backend\Form\Element\Note::class,
    ];

    /**
     * Xclassing
     */
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\In2code\Powermail\Domain\Validator\InputValidator::class] = [
        'className' => \In2code\PowermailCond\Domain\Validator\ConditionAwareValidator::class,
    ];
})();
