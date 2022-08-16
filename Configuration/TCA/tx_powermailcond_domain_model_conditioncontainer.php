<?php

declare(strict_types=1);

return [
    'ctrl' => [
        'title' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditioncontainer',
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
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general, title, form, conditions, note,
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
                'foreign_table' => 'tx_powermailcond_domain_model_conditioncontainer',
                'foreign_table_where' => 'AND {#tx_powermailcond_domain_model_conditioncontainer}.{#pid}=###CURRENT_PID### AND {#tx_powermailcond_domain_model_conditioncontainer}.{#sys_language_uid} IN (-1,0)',
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
                'default' => 0,
            ],
        ],
        'note' => [
            'exclude' => true,
            'config' => [
                'type' => 'user',
                'renderType' => 'powermailCondShowNote',
                'parameters' => [],
            ],
        ],
        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditioncontainer.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'default' => '',
            ],
        ],
        'form' => [
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'exclude' => false,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditioncontainer.form',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditioncontainer.form.pleaseChoose',
                        '',
                    ],
                ],
                'itemsProcFunc' => 'In2code\PowermailCond\UserFunc\GetPowermailFormsWithoutConditionRelation->filterForms',
                'itemsProcFunc_addFieldsets' => true,
                'maxitems' => 1,
                'size' => 1,
                'minitems' => 1,
                'requestUpdate' => 1,
                'foreign_table' => 'tx_powermail_domain_model_form',
                'foreign_table_where' => 'AND tx_powermail_domain_model_form.deleted = 0 AND tx_powermail_domain_model_form.hidden = 0 AND tx_powermail_domain_model_form.sys_language_uid IN (-1,0) order by tx_powermail_domain_model_form.title',
                'default' => 0,
            ],
        ],
        'conditions' => [
            'displayCond' => 'FIELD:form:>:0',
            'exclude' => false,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xlf:tx_powermailcond_conditioncontainer.conditions',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_powermailcond_domain_model_condition',
                'foreign_table_where' => 'AND tx_powermailcond_domain_model_condition.pid=###CURRENT_PID### ORDER BY tx_powermailcond_domain_model_condition.sorting',
                'foreign_field' => 'conditioncontainer',
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
    ],
];
