<?php
use In2code\PowermailCond\Utility\ConfigurationUtility;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:tx_powermailcond_rules',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'iconfile' => ConfigurationUtility::getIconPath(
            \In2code\PowermailCond\Domain\Model\Rule::TABLE_NAME . '.gif'
        ),
        'hideTable' => 1
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden,conditions,title,start_field,ops,cond_string,equal_field',
    ],
    'types' => [
        '0' => ['showitem' => 'conditions,title,start_field,ops,cond_string,equal_field']
    ],
    'palettes' => [
        '1' => [],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:powermail/Resources/Private/Language/locallang_db.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],

        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_rules.title',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'default' => ''
            ]
        ],
        'start_field' => [
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_rules.startField',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_rules.startField.I.0',
                        '0'
                    ],
                ],
                'itemsProcFunc' => 'In2code\PowermailCond\UserFunc\GetPowermailFields->getFields',
                // allow only this types of fields in selector
                'itemsProcFuncValue' => 'input,textarea,select,radio,check',
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required',
                'default' => 0
            ]
        ],
        'ops' => [
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_rules.operator',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    // title operators
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_rules.operator.I.operators',
                        '--div--'
                    ],
                    // is set
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_rules.operator.I.0',
                        \In2code\PowermailCond\Domain\Model\Rule::OPERATOR_IS_SET
                    ],
                    // is not set
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_rules.operator.I.1',
                        \In2code\PowermailCond\Domain\Model\Rule::OPERATOR_NOT_IS_SET
                    ],
                    // title operatorsComparisonValue
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_rules.operator.I.operatorsComparisonValue',
                        '--div--'
                    ],
                    // contains
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_rules.operator.I.2',
                        \In2code\PowermailCond\Domain\Model\Rule::OPERATOR_CONTAINS_VALUE
                    ],
                    // contains not
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_rules.operator.I.3',
                        \In2code\PowermailCond\Domain\Model\Rule::OPERATOR_NOT_CONTAINS_VALUE
                    ],
                    // is
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_rules.operator.I.4',
                        \In2code\PowermailCond\Domain\Model\Rule::OPERATOR_IS
                    ],
                    // is not
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_rules.operator.I.5',
                        \In2code\PowermailCond\Domain\Model\Rule::OPERATOR_NOT_IS
                    ],
                    // is greater than
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_rules.operator.I.6',
                        \In2code\PowermailCond\Domain\Model\Rule::OPERATOR_GREATER_THAN
                    ],
                    // is less than
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_rules.operator.I.7',
                        \In2code\PowermailCond\Domain\Model\Rule::OPERATOR_LESS_THAN
                    ],
                    // title operatorsComparisonField
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_rules.operator.I.operatorsComparisonField',
                        '--div--'
                    ],
                    // contains value from field
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_rules.operator.I.8',
                        \In2code\PowermailCond\Domain\Model\Rule::OPERATOR_CONTAINS_VALUE_FROM_FIELD
                    ],
                    // contains not value from field
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_rules.operator.I.9',
                        \In2code\PowermailCond\Domain\Model\Rule::OPERATOR_NOT_CONTAINS_VALUE_FROM_FIELD
                    ],
                ],
                'size' => 1,
                'maxitems' => 1,
                'default' => 0
            ]
        ],
        'cond_string' => [
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_rules.condstring',
            'config' => [
                'type' => 'text',
                'cols' => '30',
                'rows' => '2',
                'default' => ''
            ],
            // show only if ops value is greater than 1
            'displayCond' => 'FIELD:ops:IN:2,3,4,5,6,7'
        ],
        'equal_field' => [
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_rules.equalField',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                        'tx_powermailcond_rules.equalField.I.0',
                        '0'
                    ],
                ],
                'itemsProcFunc' => 'In2code\PowermailCond\UserFunc\GetPowermailFields->getFields',
                // allow only this types of fields in selector
                'itemsProcFuncValue' => 'input,textarea,select,radio,check',
                'size' => 1,
                'maxitems' => 1,
                'default' => 0,
                'eval' => 'int'
            ],
            'displayCond' => 'FIELD:ops:IN:8,9'
        ],
        'conditions' => [
            'l10n_mode' => 'exclude',
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_rules.condition',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => \In2code\PowermailCond\Domain\Model\Condition::TABLE_NAME,
                'foreign_table_where' =>
                    'AND ' . \In2code\PowermailCond\Domain\Model\Condition::TABLE_NAME . '.pid=###CURRENT_PID### AND 
                    ' . \In2code\PowermailCond\Domain\Model\Condition::TABLE_NAME . '.sys_language_uid IN 
                    (-1,###REC_FIELD_sys_language_uid###)',
                'default' => 0
            ]
        ]
    ]
];
