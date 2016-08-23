<?php

########################################################################
# Extension Manager/Repository config file for ext "powermail_cond".
#
# Auto generated 29-10-2012 13:53
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = [
    'title' => 'Powermail Conditions',
    'description' => 'Add conditions (via AJAX) to powermail forms for fields and pages',
    'category' => 'plugin',
    'shy' => 0,
    'version' => '3.2.1',
    'dependencies' => 'powermail',
    'conflicts' => '',
    'priority' => '',
    'loadOrder' => '',
    'module' => '',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearcacheonload' => 1,
    'lockType' => '',
    'author' => 'Alex Kellner',
    'author_email' => 'alexander.kellner@in2code.de',
    'author_company' => 'in2code.de',
    'CGLcompliance' => '',
    'CGLcompliance_note' => '',
    'constraints' => [
        'depends' => [
            'powermail' => '2.24.0-3.99.99',
            'extbase' => '6.2.0-7.99.99',
            'fluid' => '6.2.0-7.99.99',
            'typo3' => '6.2.0-7.99.99',
            'php' => '5.5.0-0.0.0'
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    '_md5_values_when_last_written' => '',
];
