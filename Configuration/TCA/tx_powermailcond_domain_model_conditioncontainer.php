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
        'iconfile' => ConfigurationUtility::getIconPath(
            \In2code\PowermailCond\Domain\Model\Condition::TABLE_NAME . '.gif'
        )
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid,l18n_parent,l18n_diffsource,hidden,starttime,' .
            'endtime,title,form,conditions',
    ],
    'types' => [
        '1' => ['showitem' => 'title, form, conditions, note'],
    ],
    'palettes' => [
        '1' => []
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:powermail/Resources/Private/Language/locallang_db.xlf:LGL.language',
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
                'foreign_table' => \In2code\PowermailCond\Domain\Model\ConditionContainer::TABLE_NAME,
                'foreign_table_where' =>
                    'AND ' . \In2code\PowermailCond\Domain\Model\ConditionContainer::TABLE_NAME
                    . '.pid=###CURRENT_PID### AND ' .
                    \In2code\PowermailCond\Domain\Model\ConditionContainer::TABLE_NAME . '.sys_language_uid IN (-1,0)',
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
                'default' => 0
            ],
        ],
        'note' => [
            'exclude' => true,
            'config' => [
                'type' => 'user',
                'renderType' => 'powermailCondShowNote',
                'parameters' => []
            ],
        ],
        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_conditioncontainer.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'default' => '',
            ]
        ],
        'form' => [
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_conditioncontainer.form',
            'onChange' => 'reload',
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
                'foreign_table' => \In2code\Powermail\Domain\Model\Form::TABLE_NAME,
                'foreign_table_where' => 'AND ' . \In2code\Powermail\Domain\Model\Form::TABLE_NAME . '.deleted = 0 ' .
                    'AND ' . \In2code\Powermail\Domain\Model\Form::TABLE_NAME . '.hidden = 0 ' .
                    'AND ' . \In2code\Powermail\Domain\Model\Form::TABLE_NAME . '.sys_language_uid IN (-1,0) ' .
                    'order by ' . \In2code\Powermail\Domain\Model\Form::TABLE_NAME . '.title',
                'default' => 0
            ]
        ],
        'conditions' => [
            'displayCond' => 'FIELD:form:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:' .
                'tx_powermailcond_conditioncontainer.conditions',
            'config' => [
                'type' => 'inline',
                'foreign_table' => \In2code\PowermailCond\Domain\Model\Condition::TABLE_NAME,
                'foreign_table_where' =>
                    'AND ' . \In2code\PowermailCond\Domain\Model\Condition::TABLE_NAME . '.pid=###CURRENT_PID### '
                    . 'ORDER BY ' . \In2code\PowermailCond\Domain\Model\Condition::TABLE_NAME . '.sorting',
                'foreign_field' => 'conditioncontainer',
                'maxitems' => 99,
                'appearance' => [
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                    'useSortable' => 1,
                    'newRecordLinkAddTitle' => 1,
                    'newRecordLinkPosition' => 'both',
                ],
                'default' => 0
            ]
        ]
    ]
];
