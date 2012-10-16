<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
if (TYPO3_MODE=='BE') {
	include_once(t3lib_extMgm::extPath('powermail_cond') . 'lib/class.tx_powermailcond_fields_be.php');
}

t3lib_extMgm::addStaticFile($_EXTKEY, 'files/static/', 'Main TypoScript');
t3lib_extMgm::allowTableOnStandardPages('tx_powermailcond_conditions');
t3lib_extMgm::allowTableOnStandardPages('tx_powermailcond_rules');


$TCA['tx_powermailcond_conditions'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_conditions',
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
		'requestUpdate' => 'actions',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_powermailcond_conditions.gif'
	),
	'feInterface' => array (
		'fe_admin_fieldList' => 'sys_language_uid,l18n_parent,l18n_diffsource,hidden,starttime,endtime,title,targetField,actions,rules,conjunction',
	)
);

$TCA['tx_powermailcond_rules'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules',
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
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_powermailcond_rules.gif'
	),
	'feInterface' => array (
		'fe_admin_fieldList' => 'hidden,startField,ops,condstring'
	)
);

?>