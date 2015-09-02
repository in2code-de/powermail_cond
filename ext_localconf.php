<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

/**
 * Include Plugins
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'In2code.' . $_EXTKEY,
	'Pi1',
	array(
		'Condition' => 'buildCondition'
	),
	array(
		'Condition' => 'buildCondition'
	)
);