<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

// Show Tables in Page View
$TYPO3_CONF_VARS['EXTCONF']['cms']['db_layout']['addTables']['tx_powermailcond_domain_model_condition'][0] = array(
	'fList' => 'title',
	'icon' => TRUE,
);


/**
 * eID Scripts
 */

// eID for telling jQuery which values are allowed and which not (via AJAX)
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['powermailcond_getFieldStatus'] =
	'EXT:powermail_cond/Classes/Utility/EidGetFieldlist.php';

// eID for storing values in the session (via AJAX)
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['powermailcond_saveToSession'] =
	'EXT:powermail_cond/Classes/Utility/EidSaveInSession.php';

// eID for reading existing values from session (via AJAX)
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['powermailcond_readSession'] =
	'EXT:powermail_cond/Classes/Utility/EidReadSession.php';

// eID to clean session to a form completely (via AJAX)
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['powermailcond_clearSession'] =
	'EXT:powermail_cond/Classes/Utility/EidClearSession.php';