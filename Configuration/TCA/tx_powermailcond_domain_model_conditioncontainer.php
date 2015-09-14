<?php
return array(
	'ctrl' => array(
		'title' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditioncontainer',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField' => 'sys_language_uid',
		'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'requestUpdate' => 'form',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('powermail_cond') .
			'Resources/Public/Icons/icon_tx_powermailcond_conditions.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid,l18n_parent,l18n_diffsource,hidden,starttime,
			endtime,title,form,conditions',
	),
	'types' => array(
		'1' => array('showitem' => 'title, form, conditions'),
	),
	'palettes' => array(
		'1' => array(),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				),
			),
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
				'foreign_table' => 'tx_powermailcond_conditioncontainer',
				'foreign_table_where' =>
					'AND tx_powermailcond_conditioncontainer.pid=###CURRENT_PID### AND tx_powermailcond_conditioncontainer.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => array (
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'title' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditioncontainer.title',
			'config' => Array (
				'type' => 'input',
				'size' => '30',
			)
		),
		'form' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditioncontainer.form',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array(
						'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditioncontainer.form.pleaseChoose',
						''
					)
				),
				'itemsProcFunc' => 'In2code\PowermailCond\UserFunc\GetPowermailFormsWithoutConditionRelation->filterForms',
				'itemsProcFunc_addFieldsets' => TRUE,
				'maxitems' => 1,
				'size' => 1,
				'minitems' => 1,
				'requestUpdate' => 1,
				'foreign_table' => 'tx_powermail_domain_model_forms',
				'foreign_table_where' => '
					AND tx_powermail_domain_model_forms.deleted = 0
					AND tx_powermail_domain_model_forms.hidden = 0
					AND tx_powermail_domain_model_forms.sys_language_uid = 0
					order by tx_powermail_domain_model_forms.title
				'
			)
		),
		'conditions' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditioncontainer.conditions',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_powermailcond_domain_model_condition',
				'foreign_table_where' =>
					'AND tx_powermailcond_domain_model_condition.pid=###CURRENT_PID### ORDER BY tx_powermailcond_domain_model_condition.sorting',
				'foreign_field' => 'conditioncontainer',
				'maxitems' => 99,
				'appearance' => array(
					'collapseAll' => 1,
					'expandSingle' => 1,
					'useSortable' => 1,
					'newRecordLinkAddTitle' => 1,
					'newRecordLinkPosition' => 'both',
				),
			),
			'displayCond' => 'FIELD:form:>:0'
		),
	),
);
