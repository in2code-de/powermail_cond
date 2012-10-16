<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_powermailcond_conditions'] = array (
	'ctrl' => $TCA['tx_powermailcond_conditions']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l18n_parent,l18n_diffsource,hidden,starttime,endtime,title,targetField,actions,filterSelectField,rules,conjunction'
	),
	'types' => array (
		'0' => array('showitem' => '--palette--;;1, targetField, --palette--;;2, rules, conjunction, sys_language_uid, l18n_parent, l18n_diffsource')
	),
	'palettes' => array (
		'1' => array('showitem' => 'title, starttime, endtime, hidden'),
		'2' => array('showitem' => 'actions, filterSelectField')
	),
	'feInterface' => $TCA['tx_powermailcond_conditions']['feInterface'],
	'columns' => array (
		'sys_language_uid' => array (
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l18n_parent' => array (
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'tx_powermailcond_conditions',
				'foreign_table_where' => 'AND tx_powermailcond_conditions.pid=###CURRENT_PID### AND tx_powermailcond_conditions.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => array (
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'hidden' => array (
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime' => array (
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				//'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array (
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				//'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array (
					'upper' => mktime(0, 0, 0, 12, 31, 2020),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		'title' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditions.title',
			'config' => Array (
				'type' => 'input',
				'size' => '30',
			)
		),
		'targetField' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditions.targetField',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditions.targetField.I.0', '0'),
				),
				'itemsProcFunc' => 'tx_powermailcond_fields_be->fieldname',
				'itemsProcFunc_addFieldsets' => 1, // add fieldsets
				'size' => 1,
				'maxitems' => 1,
				'eval' => 'required'
			)
		),
		'actions' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditions.action',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditions.action.I.main', '--div--'), // title main
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditions.action.I.0', '0'), // hide
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditions.action.I.1', '1'), // unhide
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditions.action.I.additional', '--div--'), // title additional
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditions.action.I.2', '2'), // filter selectbox
				),
				'size' => 1,
				'maxitems' => 1,
			)
		),
		'filterSelectField' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditions.filterSelectField',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
				),
				'itemsProcFunc' => 'tx_powermailcond_fields_be->valuesFromPowermailSelectbox',
				'size' => 4,
				'maxitems' => 1000
			),
			'displayCond' => 'FIELD:actions:IN:2' // show only if ops value is greater than 1
		),
		'rules' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditions.rules',
			'config' => Array (
				'type' => 'inline',
				'foreign_table' => 'tx_powermailcond_rules',
				'foreign_table_where' => 'AND tx_powermailcond_rules.pid=###CURRENT_PID### ORDER BY tx_powermailcond_rules.uid',
				'foreign_field' => 'conditions',
				'maxitems' => 99,
				'appearance' => array(
					'collapseAll' => 1,
					'expandSingle' => 1,
					'useSortable' => 1,
					'newRecordLinkAddTitle' => 1,
					'newRecordLinkPosition' => 'both',
				),
			)
		),
		'conjunction' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditions.conjunction',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditions.conjunction.I.1', 'OR'), // OR
					Array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditions.conjunction.I.0', 'AND'), // AND
				),
				'size' => 1,
				'maxitems' => 1,
			)
		),
	),
);

?>