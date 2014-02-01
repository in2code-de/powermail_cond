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
 * This class is for storing values to session on every fieldchange (via AJAX)
 *
 * @author	Alex Kellner <alexander.kellner@in2code.de>, in2code.
 * @package	TYPO3
 * @subpackage	Tx_PowermailCond_Utility_EidReadSession
 */
class Tx_PowermailCond_Utility_EidSaveInSession {

	/**
	 * The extension key
	 *
	 * @var string
	 */
	public $extKey = 'powermail_cond';

	/**
	 * Prefix Id
	 *
	 * @var string
	 */
	public $prefixId = 'tx_powermailcond_pi1';

	/**
	 * @var Tx_PowermailCond_Utility_Div
	 */
	protected $div;

	/**
	 * Read values from session - example: 18:braun;17:rot;12:xd;11:fc;
	 *
	 * @return bool
	 */
	public function main() {
		$GLOBALS['TSFE']->sesData = tslib_eidtools::initFeUser();
		$piVars = t3lib_div::_GP($this->prefixId);

		$uid = intval($piVars['uid']);
		$form = intval($piVars['form']);
		$value = htmlspecialchars($piVars['value']);

		// start
		if ($uid === 0 || $form === 0) {
			return FALSE;
		}
		// save single value in session
		$this->div->saveValueToSession($value, $form, $uid);

		return FALSE;
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

		$this->div = t3lib_div::makeInstance('Tx_PowermailCond_Utility_Div');
	}
}

$eid = t3lib_div::makeInstance('Tx_PowermailCond_Utility_EidSaveInSession', $GLOBALS['TYPO3_CONF_VARS']);
echo $eid->main();