<?php

use Symfony\Component\Finder\Finder;
use TYPO3\CodingStandards\CsFixerConfig;

$config = CsFixerConfig::create();
$rules = $config->getRules();
$rules['ordered_imports'] = ['sort_algorithm' => 'alpha', 'imports_order' => ['class', 'function', 'const']];
$config->setRules($rules);

/** @var Finder $finder */
$finder = $config->getFinder();
$finder->in(__DIR__ . '/../../')
    ->exclude('.Build')
    ->exclude('.ddev')
    ->exclude('.github')
    ->exclude('config');

return $config;
