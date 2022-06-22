<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Powermail Conditions',
    'description' => 'Add conditions (via AJAX) to powermail forms for fields and pages',
    'category' => 'plugin',
    'version' => '8.3.0',
    'state' => 'stable',
    'author' => 'Alex Kellner',
    'author_email' => 'alexander.kellner@in2code.de',
    'author_company' => 'in2code.de',
    'constraints' => [
        'depends' => [
            'powermail' => '8.0.0-10.99.99',
            'typo3' => '10.4.0-11.5.99'
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    '_md5_values_when_last_written' => '',
];
