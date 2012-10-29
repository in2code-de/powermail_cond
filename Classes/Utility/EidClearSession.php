<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Alexander Kellner <alexander.kellner@in2code.de>, in2code.
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
 * This class cleans the full session
 *
 * @author	Alex Kellner <alexander.kellner@in2code.de>, in2code.
 * @package	TYPO3
 * @subpackage	Tx_PowermailCond_Utility_EidClearSession
 */
class Tx_PowermailCond_Utility_EidClearSession extends tslib_pibase {

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
	 * Write values to session - main method called via AJAX
	 *
	 * @return	void
	 */
	public function main() {
		// config
		$this->getCObj(); // enable TSFE globals
		$GLOBALS['TSFE']->sesData = tslib_eidtools::initFeUser();
		$piVars = t3lib_div::_GP($this->prefixId); // GET param
		$form = intval($piVars['form']); // uid of current field
		$this->div->cleanfullSession($form);
	}

	/**
	 * Initialize cObj and TSFE Globals
	 *
	 * @return	object	cObj
	 */
	private function getCObj() {
		$this->div = t3lib_div::makeInstance('Tx_PowermailCond_Utility_Div'); // Create new instance for div class
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

$SOBE = t3lib_div::makeInstance('Tx_PowermailCond_Utility_EidClearSession'); // make instance
echo $SOBE->main(); // print content
?>