<?php
use In2code\PowermailCond\Utility\ConfigurationUtility;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
            'tx_powermailcond_conditioncontainer',
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
        'requestUpdate' => 'form',
        'iconfile' => ConfigurationUtility::getIconPath('icon_tx_powermailcond_conditions.gif')
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid,l18n_parent,l18n_diffsource,hidden,starttime,' .
            'endtime,title,form,conditions',
    ],
    'types' => [
        '1' => ['showitem' => 'title, form, conditions'],
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
                'foreign_table' => 'tx_powermailcond_conditioncontainer',
                'foreign_table_where' => 'AND tx_powermailcond_conditioncontainer.pid=###CURRENT_PID### AND ' .
                    'tx_powermailcond_conditioncontainer.sys_language_uid IN (-1,0)',
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
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
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
                'tx_powermailcond_conditioncontainer.title',
            'config' => [
                'type' => 'input',
                'size' => '30',
            ]
        ],
        'form' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_conditioncontainer.form',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_conditioncontainer.form.pleaseChoose',
                        ''
                    ]
                ],
                'itemsProcFunc' =>
                    'In2code\PowermailCond\UserFunc\GetPowermailFormsWithoutConditionRelation->filterForms',
                'itemsProcFunc_addFieldsets' => true,
                'maxitems' => 1,
                'size' => 1,
                'minitems' => 1,
                'requestUpdate' => 1,
                'foreign_table' => 'tx_powermail_domain_model_forms',
                'foreign_table_where' => 'AND tx_powermail_domain_model_forms.deleted = 0 ' .
                    'AND tx_powermail_domain_model_forms.hidden = 0 ' .
                    'AND tx_powermail_domain_model_forms.sys_language_uid = 0 ' .
                    'order by tx_powermail_domain_model_forms.title'
            ]
        ],
        'conditions' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_conditioncontainer.conditions',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_powermailcond_domain_model_condition',
                'foreign_table_where' => 'AND tx_powermailcond_domain_model_condition.pid=###CURRENT_PID### ' .
                    'ORDER BY tx_powermailcond_domain_model_condition.sorting',
                'foreign_field' => 'conditioncontainer',
                'maxitems' => 99,
                'appearance' => [
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                    'useSortable' => 1,
                    'newRecordLinkAddTitle' => 1,
                    'newRecordLinkPosition' => 'both',
                ],
            ],
            'displayCond' => 'FIELD:form:>:0'
        ],
    ],
];
