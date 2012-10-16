<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_powermailcond_conditions'] = array (
	'ctrl' => $TCA['tx_powermailcond_conditions']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l18n_parent,l18n_diffsource,hidden,starttime,endtime,title,targetField,actions,filterSelectField,rules,conjunction'
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
			'label' => 'LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_conditions.title',		
			'config' => Array (
				'type' => 'input',	
				'size' => '30',
			)
		),
		'targetField' => Array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_conditions.targetField',		
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_conditions.targetField.I.0', '0'),
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
            'label' => 'LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_conditions.action',
            'config' => Array (
                'type' => 'select',
                'items' => Array (
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_conditions.action.I.main', '--div--'), // title main
                    Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_conditions.action.I.0', '0'), // hide
                    Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_conditions.action.I.1', '1'), // unhide
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_conditions.action.I.additional', '--div--'), // title additional
                    Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_conditions.action.I.2', '2'), // filter selectbox
               	),
                'size' => 1,
                'maxitems' => 1,
            )
        ),
		'filterSelectField' => Array (		
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_conditions.filterSelectField',		
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
			'label' => 'LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_conditions.rules',		
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
			'label' => 'LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_conditions.conjunction',		
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_conditions.conjunction.I.1', 'OR'), // OR
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_conditions.conjunction.I.0', 'AND'), // AND
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => '--palette--;;1, targetField, --palette--;;2, rules, conjunction, sys_language_uid, l18n_parent, l18n_diffsource')
	),
	'palettes' => array (
		'1' => array('showitem' => 'title, starttime, endtime, hidden'),
		'2' => array('showitem' => 'actions, filterSelectField')
	)
);



$TCA['tx_powermailcond_rules'] = array (
	'ctrl' => $TCA['tx_powermailcond_rules']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,startField,ops,condstring,equalField'
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
			'label' => 'LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.title',		
			'config' => Array (
				'type' => 'input',	
				'size' => '30',
			)
		),
		'startField' => Array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.startField',		
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.startField.I.0', '0'),
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
			'label' => 'LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.operator',		
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					//Array('', ''), // empty
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.operator.I.operators', '--div--'), // title operators
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.operator.I.0', '0'), // is set
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.operator.I.1', '1'), // is not set
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.operator.I.operatorsComparisonValue', '--div--'), // title operatorsComparisonValue
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.operator.I.2', '2'), // contains
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.operator.I.3', '3'), // contains not
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.operator.I.4', '4'), // is
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.operator.I.5', '5'), // is not
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.operator.I.6', '6'), // is greater than
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.operator.I.7', '7'), // is less than
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.operator.I.operatorsComparisonField', '--div--'), // title operatorsComparisonField
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.operator.I.8', '8'), // contains value from field
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.operator.I.9', '9'), // contains not value from field
				),
				'size' => 1,	
				'maxitems' => 1
			)
		),
		'condstring' => Array (
			'exclude' => 1,		
			'label' => 'LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.condstring',		
			'config' => Array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '2',
			),
			'displayCond' => 'FIELD:ops:IN:2,3,4,5,6,7' // show only if ops value is greater than 1
		),
		'equalField' => Array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.equalField',		
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('LLL:EXT:powermail_cond/locallang_db.xml:tx_powermailcond_rules.equalField.I.0', '0'),
				),
				'itemsProcFunc' => 'tx_powermailcond_fields_be->fieldname',	
				'itemsProcFuncValue' => '"text","textarea","select","radio"', // allow only this types of fields in selector
				'size' => 1,	
				'maxitems' => 1
			),
			'displayCond' => 'FIELD:ops:IN:8,9' // show only if ops value is greater than 1
		),
	),
	'types' => array (
		'0' => array('showitem' => '--palette--;;1,startField,--palette--;;2')
	),
	'palettes' => array (
		'1' => array('showitem' => 'title, hidden'),
		'2' => array('showitem' => 'ops,condstring,equalField')
	)
);
?>