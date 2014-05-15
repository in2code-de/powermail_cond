<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Alex Kellner <alexander.kellner@in2code.de>, in2code.de
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Debug Session values
 *
 * @author	Alex Kellner <alexander.kellner@in2code.de>, in2code.
 * @package	TYPO3
 * @subpackage	Tx_PowermailCond_Utility_EidDebugSession
 */
class Tx_PowermailCond_Utility_EidDebugSession {

	/**
	 * Read values from session - example: 18:braun;17:rot;12:xd;11:fc;
	 *
	 * @return bool
	 */
	public function main() {
		if (empty($GLOBALS['BE_USER']->user['uid'])) {
			return 'Please login into backend';
		}

		/* @var $div Tx_PowermailCond_Utility_Div */
		$div = t3lib_div::makeInstance('Tx_PowermailCond_Utility_Div');
		$piVars = t3lib_div::_GP('tx_powermailcond_pi1');
		if (empty($piVars['formUid'])) {
			return 'tx_powermailcond_pi1[formUid] is missing';
		}

		$sessionForm = $div->getAllSessionValuesFromForm($piVars['formUid']);
		$sessionDeRequiredFields = $div->getAllSessionValuesFromForm($piVars['formUid'], 'deRequiredFields');

		t3lib_utility_Debug::debug($sessionForm, 'Values in Session');
		t3lib_utility_Debug::debug($sessionDeRequiredFields, 'Disabled Required Fields');
	}

	/**
	 * Initialize eID
	 */
	public function __construct($TYPO3_CONF_VARS) {
		$userObj = tslib_eidtools::initFeUser();
		$GLOBALS['TSFE'] = t3lib_div::makeInstance('tslib_fe', $TYPO3_CONF_VARS, 32, 0, TRUE);
		$GLOBALS['TSFE']->connectToDB();
		$GLOBALS['TSFE']->fe_user = $userObj;
		$GLOBALS['TSFE']->id = t3lib_div::_GET('id');
		$GLOBALS['TSFE']->determineId();
		$GLOBALS['TSFE']->getCompressedTCarray();
		$GLOBALS['TSFE']->initTemplate();
		$GLOBALS['TSFE']->getConfigArray();
		$GLOBALS['TSFE']->includeTCA();
		$GLOBALS['BE_USER'] = t3lib_div::makeInstance('t3lib_beUserAuth');
		$GLOBALS['BE_USER']->start();
		$GLOBALS['BE_USER']->backendCheckLogin();
	}
}

$eid = t3lib_div::makeInstance('Tx_PowermailCond_Utility_EidDebugSession', $GLOBALS['TYPO3_CONF_VARS']);
echo $eid->main();