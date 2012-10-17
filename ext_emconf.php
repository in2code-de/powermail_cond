<?php

########################################################################
# Extension Manager/Repository config file for ext "powermail_cond".
#
# Auto generated 16-10-2012 15:32
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
	'version' => '2.0.0alpha',
	'dependencies' => '',
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
			'powermail' => '2.0.3-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:17:{s:12:"ext_icon.gif";s:4:"014a";s:17:"ext_localconf.php";s:4:"8fd8";s:14:"ext_tables.php";s:4:"7b13";s:14:"ext_tables.sql";s:4:"0755";s:36:"icon_tx_powermailcond_conditions.gif";s:4:"bd1e";s:31:"icon_tx_powermailcond_rules.gif";s:4:"a2f9";s:16:"locallang_db.xml";s:4:"3438";s:7:"tca.php";s:4:"9a53";s:14:"doc/manual.sxw";s:4:"9c63";s:26:"files/js/powermail_cond.js";s:4:"5c2c";s:26:"files/static/constants.txt";s:4:"bfff";s:22:"files/static/setup.txt";s:4:"75c5";s:48:"lib/class.tx_powermailcond_ajaxFieldList_eid.php";s:4:"3f0f";s:53:"lib/class.tx_powermailcond_ajaxWriteInSession_eid.php";s:4:"cd8f";s:34:"lib/class.tx_powermailcond_div.php";s:4:"0352";s:40:"lib/class.tx_powermailcond_fields_be.php";s:4:"38f8";s:43:"lib/class.tx_powermailcond_pidContainer.php";s:4:"edfc";}',
);

?>