<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Alexander Kellner <alexander.kellner@in2code.de>, in2code.
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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

require_once(PATH_t3lib . 'class.t3lib_befunc.php');
require_once(PATH_t3lib . 'stddb/tables.php');
require_once(t3lib_extMgm::extPath('cms', 'ext_tables.php'));
require_once(PATH_tslib . 'class.tslib_pibase.php');
require_once(t3lib_extMgm::extPath('powermail_cond') . 'Classes/Utility/Div.php');

/**
 * This class is for storing values to the session on every fieldchange (via AJAX)
 *
 * @author	Alex Kellner <alexander.kellner@in2code.de>, in2code.
 * @package	TYPO3
 * @subpackage	tx_powermailcond_ajaxWriteInSession_eid
 */
class Tx_PowermailCond_Utility_EidSaveInSession extends tslib_pibase {

	/**
	 * The extension key
	 *
	 * @var string
	 */
	public $extKey = 'powermail_cond'; // Extension key

	/**
	 * Prefix Id
	 *
	 * @var string
	 */
	public $prefixId = 'tx_powermailcond_pi1';

	/**
	 * Debug mode
	 *
	 * @var bool
	 */
	private $debug = false;

	/**
	 * Write values to session - main method called via AJAX
	 *
	 * @return	void
	 */
	public function main() {
		return 'lalelu';
		/*
		// config
		$this->getCObj(); // enable TSFE globals
		$GLOBALS['TSFE']->sesData = tslib_eidtools::initFeUser();
		$piVars = t3lib_div::_GP($this->prefixId); // GET param
		$uids = t3lib_div::trimExplode('_', $piVars['uid']);
		$uid = intval($uids[0]); // uid of current field
		$value = htmlspecialchars($piVars['value']); // value from current field
		$this->div = t3lib_div::makeInstance('tx_powermailcond_div'); // Create new instance for div class
		$ttcontent_uid = $this->div->getTtcontentUid($uid); // get tt_content uid to current field

		// start
		if ($uid == 0) {
			return false;
		}
		if (isset($uids[1])) { // second level
			$oldSession = array();
			$arr['uid' . $uid] = $GLOBALS['TSFE']->fe_user->sesData['powermail_' . $ttcontent_uid]['uid' . $uid];
			$arr['uid' . $uid][$uids[1]] = $value;
			$this->div->saveInSession($arr, 'powermail_' . $ttcontent_uid); // save values to session
		} else { // first level
			$arr = array(
				'uid' . $uid => $value
			);
			$this->div->saveInSession($arr, 'powermail_' . $ttcontent_uid); // save values to session
		}

		if ($this->debug) {
			t3lib_div::debug($arr, 'powermail_' . $ttcontent_uid);
		}
		*/
	}

	/**
	 * Get tt_content UID from field UID
	 *
	 * @param	integer	$fuid: UID of tx_powermail_fields
	 * @return	integer	$uid: tt_content UID
	 */
	private function getTtcontentUid($fuid) {
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
	 * Initialize cObj and TSFE Globals
	 *
	 * @return	object	cObj
	 */
	private function getCObj() {
		$this->div = t3lib_div::makeInstance('tx_powermailcond_div'); // Create new instance for div class
		$userObj = tslib_eidtools::initFeUser();
		$temp_TSFEclassName = t3lib_div::makeInstance('tslib_fe');
		$GLOBALS['TSFE'] = new $temp_TSFEclassName($TYPO3_CONF_VARS, 32, 0, true);
		$GLOBALS['TSFE']->connectToDB();
		$GLOBALS['TSFE']->fe_user = $userObj;
		$GLOBALS['TSFE']->id = t3lib_div::_GET('id');
		$GLOBALS['TSFE']->determineId();
		$GLOBALS['TSFE']->getCompressedTCarray();
		$GLOBALS['TSFE']->initTemplate();
		$GLOBALS['TSFE']->getConfigArray();
		$GLOBALS['TSFE']->includeTCA();

		return t3lib_div::makeInstance('tslib_cObj');
	}

}

$SOBE = t3lib_div::makeInstance('Tx_PowermailCond_Utility_EidSaveInSession'); // make instance
echo $SOBE->main(); // print content
?>