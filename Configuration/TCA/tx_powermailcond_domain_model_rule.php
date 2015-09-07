<?php
return array(
	'ctrl' => array(
		'title' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden'
		),
		'requestUpdate' => 'ops',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('powermail_cond') .
			'Resources/Public/Icons/icon_tx_powermailcond_rules.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'hidden,start_field,ops,cond_string,equal_field',
	),
	'types' => array(
		'0' => array('showitem' => 'title,start_field,ops,cond_string,equal_field')
	),
	'palettes' => array(
		'1' => array(),
	),
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'title' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'start_field' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.startField',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.startField.I.0', '0'),
				),
				'itemsProcFunc' => 'In2code\PowermailCond\UserFunc\GetPowermailFields->getFields',
				// allow only this types of fields in selector
				'itemsProcFuncValue' => 'input,textarea,select,radio,check',
				'size' => 1,
				'maxitems' => 1,
				'eval' => 'required'
			)
		),
		'ops' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator',
			'config' => array(
				'type' => 'select',
				'items' => array(
					// title operators
					array(
						'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.operators',
						'--div--'
					),

					// is set
					array(
						'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.0',
						\In2code\PowermailCond\Domain\Model\Rule::OPERATOR_IS_SET
					),

					// is not set
					array(
						'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.1',
						\In2code\PowermailCond\Domain\Model\Rule::OPERATOR_NOT_IS_SET
					),

					// title operatorsComparisonValue
					array(
						'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.operatorsComparisonValue',
						'--div--'
					),

					// contains
					array(
						'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.2',
						\In2code\PowermailCond\Domain\Model\Rule::OPERATOR_CONTAINS_VALUE
					),

					// contains not
					array(
						'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.3',
						\In2code\PowermailCond\Domain\Model\Rule::OPERATOR_NOT_CONTAINS_VALUE
					),

					// is
					array(
						'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.4',
						\In2code\PowermailCond\Domain\Model\Rule::OPERATOR_IS
					),

					// is not
					array(
						'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.5',
						\In2code\PowermailCond\Domain\Model\Rule::OPERATOR_NOT_IS
					),

					// is greater than
					array(
						'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.6',
						\In2code\PowermailCond\Domain\Model\Rule::OPERATOR_GREATER_THAN
					),

					// is less than
					array(
						'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.7',
						\In2code\PowermailCond\Domain\Model\Rule::OPERATOR_LESS_THAN
					),

					// title operatorsComparisonField
					array(
						'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.operatorsComparisonField',
						'--div--'
					),

					// contains value from field
					array(
						'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.8',
						\In2code\PowermailCond\Domain\Model\Rule::OPERATOR_CONTAINS_VALUE_FROM_FIELD
					),

					// contains not value from field
					array(
						'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.operator.I.9',
						\In2code\PowermailCond\Domain\Model\Rule::OPERATOR_NOT_CONTAINS_VALUE_FROM_FIELD
					),
				),
				'size' => 1,
				'maxitems' => 1
			)
		),
		'cond_string' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.condstring',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '2',
			),
			// show only if ops value is greater than 1
			'displayCond' => 'FIELD:ops:IN:2,3,4,5,6,7'
		),
		'equal_field' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.equalField',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array(
						'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules.equalField.I.0',
						'0'
					),
				),
				'itemsProcFunc' => 'tx_powermailcond_fields_be->fieldname',
				// allow only this types of fields in selector
				'itemsProcFuncValue' => '"text","textarea","select","radio"',
				'size' => 1,
				'maxitems' => 1
			),
			'displayCond' => 'FIELD:ops:IN:8,9'
		),
	),
);
