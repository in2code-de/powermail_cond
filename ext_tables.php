<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

// Fieldlisting in Backend
if (TYPO3_MODE == 'BE') {
	require_once(
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('powermail_cond') . 'Classes/Utility/Tca/FieldlistingBackend.php'
	);
}

// Add TypoScript Static Template
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/', 'Main TypoScript');

// Configuration for Conditions
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
	'tx_powermailcond_domain_model_condition',
	'EXT:powermail_cond/Resources/Private/Language/locallang_csh_tx_powermailcond_domain_model_condition.xml'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_powermailcond_domain_model_condition');
$TCA['tx_powermailcond_domain_model_condition'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditions',
		'label'     => 'title',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => array (
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime'
		),
		'requestUpdate' => 'actions,form',
		'dynamicConfigFile' =>
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Condition.php',
		'iconfile' =>
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) .
			'/Resources/Public/Icons/icon_tx_powermailcond_conditions.gif'
	),
);

// Configuration for Rules
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
	'tx_powermailcond_domain_model_rule',
	'EXT:powermail_cond/Resources/Private/Language/locallang_csh_tx_powermailcond_domain_model_rule.xml'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_powermailcond_domain_model_rule');
$TCA['tx_powermailcond_domain_model_rule'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules',
		'label'     => 'title',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => array (
			'disabled' => 'hidden'
		),
		'requestUpdate' => 'ops',
		'dynamicConfigFile' =>
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Rule.php',
		'iconfile' =>
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) .
			'/Resources/Public/Icons/icon_tx_powermailcond_rules.gif'
	),
);