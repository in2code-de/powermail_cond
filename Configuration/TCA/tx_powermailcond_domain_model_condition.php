<?php
use In2code\PowermailCond\Utility\ConfigurationUtility;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_conditions',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'languageField' => 'sys_language_uid',
        'sortby' => 'sorting',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'iconfile' => ConfigurationUtility::getIconPath('icon_tx_powermailcond_conditions.gif')
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid,l18n_parent,l18n_diffsource,hidden,starttime,
			endtime,conditioncontainer,title,target_field,actions,filter_select_field,rules,conjunction',
    ],
    'types' => [
        '1' => ['showitem' =>
            'conditioncontainer, title, target_field, actions, filter_select_field, conjunction, rules'],
    ],
    'palettes' => [
        '1' => [],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    [
                        'LLL:EXT:lang/locallang_general.xml:LGL.allLanguages',
                        -1
                    ],
                    [
                        'LLL:EXT:lang/locallang_general.xml:LGL.default_value',
                        0
                    ]
                ],
                'default' => 0
            ],
        ],
        'l18n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        '',
                        0
                    ],
                ],
                'foreign_table' => 'tx_powermailcond_domain_model_condition',
                'foreign_table_where' => 'AND tx_powermailcond_domain_model_condition.pid=###CURRENT_PID### AND ' .
                    'tx_powermailcond_domain_model_condition.sys_language_uid IN (-1,0)',
            ]
        ],
        'l18n_diffsource' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
                'size' => 13,
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
                'size' => 13,
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],

        'title' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_conditions.title',
            'config' => [
                'type' => 'input',
                'size' => '30',
            ]
        ],
        'rules' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_conditions.rules',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_powermailcond_domain_model_rule',
                'foreign_table_where' => 'AND tx_powermailcond_domain_model_rule.pid=###CURRENT_PID### ' .
                    'ORDER BY tx_powermailcond_domain_model_rule.sorting',
                'foreign_field' => 'conditions',
                'maxitems' => 99,
                'appearance' => [
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                    'useSortable' => 1,
                    'newRecordLinkAddTitle' => 1,
                    'newRecordLinkPosition' => 'both',
                ],
            ],
        ],
        'conjunction' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_conditions.conjunction',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    // OR
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_conditions.conjunction.I.1',
                        \In2code\PowermailCond\Domain\Model\Condition::CONJUNCTION_OR
                    ],
                    // AND
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_conditions.conjunction.I.0',
                        \In2code\PowermailCond\Domain\Model\Condition::CONJUNCTION_AND
                    ],
                ],
                'size' => 1,
                'maxitems' => 1,
            ],
        ],
        'target_field' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_conditions.targetField',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_conditions.targetField.I.0',
                        '0'
                    ],
                ],
                'itemsProcFunc' => \In2code\PowermailCond\UserFunc\GetPowermailFields::class . '->getFields',
                'itemsProcFunc_addFieldsets' => true,
                // allow only this types of fields in selector
                'itemsProcFuncValue' => 'input,textarea,select,check,radio,submit,captcha,reset,text,content,html,' .
                    'password,file,date,country,location,typoscript',
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required'
            ],
        ],
        'actions' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_conditions.action',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    // title main
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_conditions.action.I.main',
                        '--div--'
                    ],
                    // hide
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_conditions.action.I.0',
                        \In2code\PowermailCond\Domain\Model\Condition::ACTION_HIDE
                    ],
                    // unhide
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_conditions.action.I.1',
                        \In2code\PowermailCond\Domain\Model\Condition::ACTION_UN_HIDE
                    ],
                    // title additional
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_conditions.action.I.additional',
                        '--div--'
                    ],
                ],
                'size' => 1,
                'maxitems' => 1,
            ],
        ],
        'conditioncontainer' => [
            'l10n_mode' => 'exclude',
            'exclude' => 1,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_conditions.conditioncontainer',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        '',
                        0
                    ],
                ],
                'foreign_table' => 'tx_powermailcond_domain_model_conditioncontainer',
                'foreign_table_where' => 'AND tx_powermailcond_domain_model_conditioncontainer.pid=###CURRENT_PID### ' .
                    'AND tx_powermailcond_domain_model_conditioncontainer.sys_language_uid IN ' .
                    '(-1,###REC_FIELD_sys_language_uid###)',
            ],
        ],
    ],
];
