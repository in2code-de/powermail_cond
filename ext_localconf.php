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
	'EXT:powermail_cond/Classes/Utility/Eid/GetFieldlistEid.php';

// eID for storing values in the session (via AJAX)
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['powermailcond_saveToSession'] =
	'EXT:powermail_cond/Classes/Utility/Eid/SaveInSessionEid.php';

// eID for reading existing values from session (via AJAX)
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['powermailcond_readSession'] =
	'EXT:powermail_cond/Classes/Utility/Eid/ReadSessionEid.php';

// eID to clean session to a form completely (via AJAX)
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['powermailcond_clearSession'] =
	'EXT:powermail_cond/Classes/Utility/Eid/ClearSessionEid.php';

// eID to save a field in a separate session to set to be non-mandatory
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['powermailcond_deRequiredField'] =
	'EXT:powermail_cond/Classes/Utility/Eid/DeRequiredFieldEid.php';

// eID to save some fields in a separate session to set to be non-mandatory
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['powermailcond_deRequiredFields'] =
	'EXT:powermail_cond/Classes/Utility/Eid/DeRequiredFieldsEid.php';

// eID to remove a field from session to select a field to be mandatory again
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['powermailcond_requiredField'] =
	'EXT:powermail_cond/Classes/Utility/Eid/RequiredFieldEid.php';

// eID to read all session values for debugging (only if logged in into Backend)
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['powermailcond_debugSession'] =
	'EXT:powermail_cond/Classes/Utility/Eid/DebugSessionEid.php';