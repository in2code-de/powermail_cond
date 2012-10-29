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

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Powermail Conditions',
	'description' => 'Add conditions via AJAX to powermail 2.x forms (fields and fieldsets). This extension uses jQuery as JavaScript Library.',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '2.0.0',
	'dependencies' => 'powermail',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Alex Kellner',
	'author_email' => 'alexander.kellner@in2code.de',
	'author_company' => 'in2code.',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'powermail' => '2.0.4-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:21:{s:12:"ext_icon.gif";s:4:"014a";s:17:"ext_localconf.php";s:4:"82ca";s:14:"ext_tables.php";s:4:"7580";s:14:"ext_tables.sql";s:4:"1691";s:23:"Classes/Utility/Div.php";s:4:"bf92";s:35:"Classes/Utility/EidClearSession.php";s:4:"d4b5";s:35:"Classes/Utility/EidGetFieldlist.php";s:4:"c60a";s:34:"Classes/Utility/EidReadSession.php";s:4:"e2e5";s:36:"Classes/Utility/EidSaveInSession.php";s:4:"507b";s:39:"Classes/Utility/FieldlistingBackend.php";s:4:"98aa";s:31:"Configuration/TCA/Condition.php";s:4:"c5cf";s:26:"Configuration/TCA/Rule.php";s:4:"ce4b";s:34:"Configuration/TypoScript/setup.txt";s:4:"8313";s:84:"Resources/Private/Language/locallang_csh_tx_powermailcond_domain_model_condition.xml";s:4:"d97c";s:79:"Resources/Private/Language/locallang_csh_tx_powermailcond_domain_model_rule.xml";s:4:"bb3b";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"2461";s:38:"Resources/Public/Css/PowermailCond.css";s:4:"c51b";s:59:"Resources/Public/Icons/icon_tx_powermailcond_conditions.gif";s:4:"bd1e";s:54:"Resources/Public/Icons/icon_tx_powermailcond_rules.gif";s:4:"a2f9";s:36:"Resources/Public/Js/PowermailCond.js";s:4:"d612";s:14:"doc/manual.sxw";s:4:"f97a";}',
	'suggests' => array(
	),
);

?>