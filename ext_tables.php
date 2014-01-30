<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

// Fieldlisting in Backend
if (TYPO3_MODE == 'BE') {
	include_once(t3lib_extMgm::extPath('powermail_cond') . 'Classes/Utility/FieldlistingBackend.php');
}

// Add TypoScript Static Template
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/', 'Main TypoScript');

// Configuration for Conditions
t3lib_extMgm::addLLrefForTCAdescr('tx_powermailcond_domain_model_condition', 'EXT:powermail_cond/Resources/Private/Language/locallang_csh_tx_powermailcond_domain_model_condition.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_powermailcond_domain_model_condition');
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
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Condition.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . '/Resources/Public/Icons/icon_tx_powermailcond_conditions.gif',
	),
);

// Configuration for Rules
t3lib_extMgm::addLLrefForTCAdescr('tx_powermailcond_domain_model_rule', 'EXT:powermail_cond/Resources/Private/Language/locallang_csh_tx_powermailcond_domain_model_rule.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_powermailcond_domain_model_rule');
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
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Rule.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . '/Resources/Public/Icons/icon_tx_powermailcond_rules.gif'
	),
);