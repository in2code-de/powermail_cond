<?php

declare(strict_types=1);

/**
 * @var array $EM_CONF
 * @var string $_EXTKEY
 */
$EM_CONF[$_EXTKEY] = [
    'title' => 'Powermail Conditions',
    'description' => 'Add conditions (via AJAX) to powermail forms for fields and pages',
    'category' => 'plugin',
    'version' => '9.0.1',
    'state' => 'stable',
    'author' => 'Alex Kellner',
    'author_email' => 'alexander.kellner@in2code.de',
    'author_company' => 'in2code.de',
    'constraints' => [
        'depends' => [
            'powermail' => '8.3.2-9.99.99',
            'typo3' => '11.5.0-11.99.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    '_md5_values_when_last_written' => '',
];
