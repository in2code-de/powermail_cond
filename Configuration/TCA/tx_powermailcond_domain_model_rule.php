<?php

declare(strict_types=1);

use In2code\PowermailCond\Domain\Model\Rule;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l18n_parent',
        'transOrigDiffSourceField' => 'l18n_diffsource',
        'versioningWS' => false,
        'sortby' => 'sorting',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'iconfile' => 'EXT:powermail_cond/Resources/Public/Icons/tx_powermailcond_domain_model_rule.gif',
        'hideTable' => 1,
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general, conditions,title,start_field,ops,cond_string,equal_field,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, --palette--;;language
            ',
        ],
    ],
    'palettes' => [
        'language' => [
            'showitem' => '
                sys_language_uid;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:sys_language_uid_formlabel,
                l18n_parent
            ',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l18n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => '',
                        'value' => 0,
                    ],
                ],
                'foreign_table' => 'tx_powermailcond_domain_model_rule',
                'foreign_table_where' => 'AND {#tx_powermailcond_domain_model_rule}.{#pid}=###CURRENT_PID### AND {#tx_powermailcond_domain_model_rule}.{#sys_language_uid} IN (-1,0)',
                'default' => 0,
            ],
        ],
        'l18n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:powermail/Resources/Private/Language/locallang_db.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.title',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'default' => '',
            ],
        ],
        'start_field' => [
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.startField',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.startField.I.0',
                        'value' => '0',
                    ],
                ],
                'itemsProcFunc' => 'In2code\PowermailCond\UserFunc\GetPowermailFields->getFormFieldsForRule',
                // allow only this types of fields in selector
                'itemsProcFuncValue' => 'input,textarea,select,radio,check',
                'size' => 1,
                'maxitems' => 1,
                'default' => 0,
                'required' => true,
            ],
        ],
        'ops' => [
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.operator',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    // title operators
                    [
                        'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.operator.I.operators',
                        'value' => '--div--',
                    ],
                    // is set
                    [
                        'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.operator.I.0',
                        'value' => Rule::OPERATOR_IS_SET,
                    ],
                    // is not set
                    [
                        'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.operator.I.1',
                        'value' => Rule::OPERATOR_NOT_IS_SET,
                    ],
                    // title operatorsComparisonValue
                    [
                        'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.operator.I.operatorsComparisonValue',
                        'value' => '--div--',
                    ],
                    // contains
                    [
                        'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.operator.I.2',
                        'value' => Rule::OPERATOR_CONTAINS_VALUE,
                    ],
                    // contains not
                    [
                        'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.operator.I.3',
                        'value' => Rule::OPERATOR_NOT_CONTAINS_VALUE,
                    ],
                    // is
                    [
                        'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.operator.I.4',
                        'value' => Rule::OPERATOR_IS,
                    ],
                    // is not
                    [
                        'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.operator.I.5',
                        'value' => Rule::OPERATOR_NOT_IS,
                    ],
                    // is greater than
                    [
                        'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.operator.I.6',
                        'value' => Rule::OPERATOR_GREATER_THAN,
                    ],
                    // is less than
                    [
                        'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.operator.I.7',
                        'value' => Rule::OPERATOR_LESS_THAN,
                    ],
                    // title operatorsComparisonField
                    [
                        'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.operator.I.operatorsComparisonField',
                        'value' => '--div--',
                    ],
                    // contains value from field
                    [
                        'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.operator.I.8',
                        'value' => Rule::OPERATOR_CONTAINS_VALUE_FROM_FIELD,
                    ],
                    // contains not value from field
                    [
                        'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.operator.I.9',
                        'value' => Rule::OPERATOR_NOT_CONTAINS_VALUE_FROM_FIELD,
                    ],
                ],
                'size' => 1,
                'maxitems' => 1,
                'default' => 0,
            ],
        ],
        'cond_string' => [
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.condstring',
            'config' => [
                'type' => 'text',
                'cols' => '30',
                'rows' => '2',
                'default' => '',
            ],
            // show only if ops value is greater than 1
            'displayCond' => 'FIELD:ops:IN:2,3,4,5,6,7',
        ],
        'equal_field' => [
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.equalField',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.equalField.I.0',
                        'value' => '0',
                    ],
                ],
                'itemsProcFunc' => 'In2code\PowermailCond\UserFunc\GetPowermailFields->getFormFieldsForRule',
                // allow only this types of fields in selector
                'itemsProcFuncValue' => 'input,textarea,select,radio,check',
                'size' => 1,
                'maxitems' => 1,
                'default' => 0,
                'eval' => 'int',
            ],
            'displayCond' => 'FIELD:ops:IN:8,9',
        ],
        'conditions' => [
            'exclude' => false,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_rules.condition',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => '',
                        'value' => 0,
                    ],
                ],
                'foreign_table' => 'tx_powermailcond_domain_model_condition',
                'foreign_table_where' => 'AND {#tx_powermailcond_domain_model_condition}.{#pid}=###CURRENT_PID### AND {#tx_powermailcond_domain_model_condition}.{#sys_language_uid} IN (-1,###REC_FIELD_sys_language_uid###)',
                'default' => 0,
            ],
        ],
    ],
];
