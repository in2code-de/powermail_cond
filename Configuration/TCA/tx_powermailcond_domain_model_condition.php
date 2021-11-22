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
        'iconfile' => ConfigurationUtility::getIconPath(
            \In2code\PowermailCond\Domain\Model\Condition::TABLE_NAME . '.gif'
        ),
        'hideTable' => 1
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid,l18n_parent,l18n_diffsource,hidden,starttime,
            endtime,conditioncontainer,title,target_field,actions,filter_select_field,rules,conjunction',
    ],
    'types' => [
        '1' => [
            'showitem' => 'conditioncontainer, title, target_field, actions, filter_select_field, conjunction, rules'
        ],
    ],
    'palettes' => [
        '1' => [],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'special' => 'languages',
                'renderType' => 'selectSingle',

                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0
            ],
        ],
        'l18n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:powermail/Resources/Private/Language/locallang_db.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => \In2code\PowermailCond\Domain\Model\Condition::TABLE_NAME,
                'foreign_table_where' =>
                    'AND ' . \In2code\PowermailCond\Domain\Model\Condition::TABLE_NAME . '.pid=###CURRENT_PID### AND ' .
                    \In2code\PowermailCond\Domain\Model\Condition::TABLE_NAME . '.sys_language_uid IN (-1,0)',
                'default' => 0
            ]
        ],
        'l18n_diffsource' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:powermail/Resources/Private/Language/locallang_db.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
            'default' => 0
        ],
        'starttime' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:powermail/Resources/Private/Language/locallang_db.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:powermail/Resources/Private/Language/locallang_db.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'size' => 13,
                'default' => 0
            ],
        ],

        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_conditions.title',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'default' => '',
            ]
        ],
        'rules' => [
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_conditions.rules',
            'config' => [
                'type' => 'inline',
                'foreign_table' => \In2code\PowermailCond\Domain\Model\Rule::TABLE_NAME,
                'foreign_table_where' =>
                    'AND ' . \In2code\PowermailCond\Domain\Model\Rule::TABLE_NAME . '.pid=###CURRENT_PID### ' .
                    'ORDER BY ' . \In2code\PowermailCond\Domain\Model\Rule::TABLE_NAME . '.sorting',
                'foreign_field' => 'conditions',
                'maxitems' => 99,
                'appearance' => [
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                    'useSortable' => 1,
                    'newRecordLinkAddTitle' => 1,
                    'newRecordLinkPosition' => 'both',
                ],
                'default' => 0,
            ],
        ],
        'conjunction' => [
            'exclude' => true,
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
                'default' => ''
            ],
        ],
        'target_field' => [
            'exclude' => true,
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
                'itemsProcFunc' => 'In2code\PowermailCond\UserFunc\GetPowermailFields->getFields',
                'itemsProcFunc_addFieldsets' => true,
                // allow only this types of fields in selector
                'itemsProcFuncValue' => 'input,textarea,select,check,radio,submit,captcha,reset,text,content,html,' .
                    'password,file,date,country,location,typoscript',
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required',
                'default' => '',
            ],
        ],
        'actions' => [
            'exclude' => true,
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
                'default' => ''
            ],
        ],
        'conditioncontainer' => [
            'l10n_mode' => 'exclude',
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_conditions.conditioncontainer',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => \In2code\PowermailCond\Domain\Model\ConditionContainer::TABLE_NAME,
                'foreign_table_where' =>
                    'AND ' . \In2code\PowermailCond\Domain\Model\ConditionContainer::TABLE_NAME
                    . '.pid=###CURRENT_PID### '
                    . 'AND ' . \In2code\PowermailCond\Domain\Model\ConditionContainer::TABLE_NAME
                    . '.sys_language_uid IN '
                    . '(-1,###REC_FIELD_sys_language_uid###)',
                'default' => 0
            ]
        ]
    ]
];
