<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_powermailcond_rules'] = array (
	'ctrl' => $TCA['tx_powermailcond_rules']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,startField,ops,condstring,equalField'
	),
	'types' => array (
		'0' => array('showitem' => '--palette--;;1,startField,--palette--;;2')
	),
	'palettes' => array (
		'1' => array('showitem' => 'title, hidden'),
		'2' => array('showitem' => 'ops,condstring,equalField')
	),
	'feInterface' => $TCA['tx_powermailcond_rules']['feInterface'],
	'columns' => array (
		'hidden' => array (
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'title' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.title',
			'config' => Array (
				'type' => 'input',
				'size' => '30',
			)
		),
		'startField' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.startField',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.startField.I.0', '0'),
				),
				'itemsProcFunc' => 'tx_powermailcond_fields_be->fieldname',
				'itemsProcFuncValue' => '"text","textarea","select","radio","check"', // allow only this types of fields in selector
				'size' => 1,
				'maxitems' => 1,
				'eval' => 'required'
			)
		),
		'ops' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					//Array('', ''), // empty
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.operators', '--div--'), // title operators
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.0', '0'), // is set
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.1', '1'), // is not set
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.operatorsComparisonValue', '--div--'), // title operatorsComparisonValue
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.2', '2'), // contains
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.3', '3'), // contains not
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.4', '4'), // is
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.5', '5'), // is not
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.6', '6'), // is greater than
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.7', '7'), // is less than
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.operatorsComparisonField', '--div--'), // title operatorsComparisonField
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.8', '8'), // contains value from field
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.9', '9'), // contains not value from field
				),
				'size' => 1,
				'maxitems' => 1
			)
		),
		'condstring' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.condstring',
			'config' => Array (
				'type' => 'text',
				'cols' => '30',
				'rows' => '2',
			),
			'displayCond' => 'FIELD:ops:IN:2,3,4,5,6,7' // show only if ops value is greater than 1
		),
		'equalField' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.equalField',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.equalField.I.0', '0'),
				),
				'itemsProcFunc' => 'tx_powermailcond_fields_be->fieldname',
				'itemsProcFuncValue' => '"text","textarea","select","radio"', // allow only this types of fields in selector
				'size' => 1,
				'maxitems' => 1
			),
			'displayCond' => 'FIELD:ops:IN:8,9' // show only if ops value is greater than 1
		),
	),
);

?>