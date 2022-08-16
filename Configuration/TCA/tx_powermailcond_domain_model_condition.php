<?php

declare(strict_types=1);

use In2code\PowermailCond\Domain\Model\Condition;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditions',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
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
        'iconfile' => 'EXT:powermail_cond/Resources/Public/Icons/tx_powermailcond_domain_model_condition.gif',
        'hideTable' => 1,
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general, title, target_field, actions, filter_select_field, conjunction, rules,
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
                        '',
                        0,
                    ],
                ],
                'foreign_table' => 'tx_powermailcond_domain_model_condition',
                'foreign_table_where' => 'AND {#tx_powermailcond_domain_model_condition}.{#pid}=###CURRENT_PID### AND {#tx_powermailcond_domain_model_condition}.{#sys_language_uid} IN (-1,0)',
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
            ],
            'default' => 0,
        ],
        'starttime' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:powermail/Resources/Private/Language/locallang_db.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
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
                'default' => 0,
            ],
        ],
        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditions.title',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'default' => '',
            ],
        ],
        'rules' => [
            'exclude' => false,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditions.rules',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_powermailcond_domain_model_rule',
                'foreign_table_where' => 'AND tx_powermailcond_domain_model_rule.pid=###CURRENT_PID### ORDER BY tx_powermailcond_domain_model_rule.sorting',
                'foreign_field' => 'conditions',
                'maxitems' => 99,
                'appearance' => [
                    'expandSingle' => 1,
                    'useSortable' => 1,
                    'newRecordLinkAddTitle' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 0,
                    'showAllLocalizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                ],
            ],
        ],
        'conjunction' => [
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'exclude' => false,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditions.conjunction',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    // OR
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditions.conjunction.I.1',
                        Condition::CONJUNCTION_OR,
                    ],
                    // AND
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditions.conjunction.I.0',
                        Condition::CONJUNCTION_AND,
                    ],
                ],
                'size' => 1,
                'maxitems' => 1,
                'default' => '',
            ],
        ],
        'target_field' => [
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'exclude' => false,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditions.targetField',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditions.targetField.I.0',
                        '0',
                    ],
                ],
                'itemsProcFunc' => 'In2code\PowermailCond\UserFunc\GetPowermailFields->getFormFieldsForCondition',
                'itemsProcFunc_addFieldsets' => true,
                // allow only this types of fields in selector
                'itemsProcFuncValue' => 'input,textarea,select,check,radio,submit,captcha,reset,text,content,html,password,file,date,country,location,typoscript',
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required',
                'default' => '',
            ],
        ],
        'actions' => [
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'exclude' => false,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditions.action',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    // title main
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditions.action.I.main',
                        '--div--',
                    ],
                    // hide
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditions.action.I.0',
                        Condition::ACTION_HIDE,
                    ],
                    // unhide
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditions.action.I.1',
                        Condition::ACTION_UN_HIDE,
                    ],
                    // title additional
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditions.action.I.additional',
                        '--div--',
                    ],
                ],
                'size' => 1,
                'maxitems' => 1,
                'default' => '',
            ],
        ],
        'conditioncontainer' => [
            'exclude' => false,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditions.conditioncontainer',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_powermailcond_domain_model_conditioncontainer',
                'foreign_table_where' => 'AND {#tx_powermailcond_domain_model_conditioncontainer}.{#pid}=###CURRENT_PID### AND {#tx_powermailcond_domain_model_conditioncontainer}.{#sys_language_uid} IN (-1,###REC_FIELD_sys_language_uid###)',
                'default' => 0,
            ],
        ],
    ],
];
