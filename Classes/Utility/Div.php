<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Alex Kellner <alexander.kellner@in2code.de>, in2code.de
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
 * Div is a class for a collection of misc functions
 *
 * @package powermail_cond
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Tx_PowermailCond_Utility_Div {

	/**
	 * Extension Key
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
	 * Write values to session
	 *
	 * @param	array	$array: Array for session store
	 * @param	string	$sesName: Session name
	 * @return	void
	 */
	public function saveInSession($array, $sesName) {
		// get current stored values from session
		$oldArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $sesName);

		// merge old with new values
		$array = array_merge((array) $oldArray, (array) $array);

		// store new session
		$GLOBALS['TSFE']->fe_user->setKey('ses', $sesName, $array); // Generate Session with piVars array
		$GLOBALS['TSFE']->storeSessionData(); // Save session
	}

	/**
	 * Return all values from the session (could be used for debugging, etc..)
	 *
	 * @param	string	$sesName: Session name
	 * @return	array	$array: with session values
	 */
	public function getAllSessionValues($sesName) {
		// get current stored values from session
		$array = $GLOBALS['TSFE']->fe_user->getKey('ses', $sesName);
		return $array;
	}

	/**
	 * Get tt_content UID from field UID
	 *
	 * @param	integer	$fuid: UID of tx_powermail_fields
	 * @return	integer	$uid: tt_content UID
	 */
	public function getTtcontentUid($fuid) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery ( // DB query
			'tt_content.uid',
			'tx_powermail_fieldsets LEFT JOIN tx_powermail_fields ON tx_powermail_fieldsets.uid = tx_powermail_fields.fieldset LEFT JOIN tt_content ON tx_powermail_fieldsets.tt_content = tt_content.uid',
			'tx_powermail_fields.uid = ' . intval($fuid),
			'',
			'',
			1
		);
		if (!$res) { // If there is a result
			return false;
		}

		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		return $row['uid'];
	}

	/**
	 * Write values to session
	 *
	 * @param	array	$conf: Configuration Array
	 * @return	array	$array: With all Startfields
	 */
	public function getStartFields($conf) {
		$array = array();
		foreach ((array) $conf as $confLevel1) {
			foreach ((array) $confLevel1 as $confLevel2) {
				if (!empty($confLevel2['startField'])) {
					$array[] = $confLevel2['startField'];
				}
			}
		}
		return $array;
	}
}
?>