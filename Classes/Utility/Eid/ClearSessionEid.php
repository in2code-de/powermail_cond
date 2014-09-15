<?php
namespace In2code\PowermailCond\Utility\Eid;

use \TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * This class is for reading values from session
 *
 * @author Alex Kellner <alexander.kellner@in2code.de>, in2code.de
 * @package TYPO3
 * @subpackage EidClearSession
 */
class ClearSessionEid {

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
	 * @var \In2code\PowermailCond\Utility\Div
	 */
	protected $div;

	/**
	 * Read values from session - example: 18:braun;17:rot;12:xd;11:fc;
	 *
	 * @return void
	 */
	public function main() {
		$GLOBALS['TSFE']->sesData = \TYPO3\CMS\Frontend\Utility\EidUtility::initFeUser();
		$piVars = GeneralUtility::_GP($this->prefixId);
		$form = intval($piVars['form']);
		$this->div->cleanfullSession($form, 'fieldSession');
		$this->div->cleanfullSession($form, 'deRequiredFields');
	}

	/**
	 * Initialize eID
	 */
	public function __construct($TYPO3_CONF_VARS) {
		$userObj = \TYPO3\CMS\Frontend\Utility\EidUtility::initFeUser();
		$GLOBALS['TSFE'] = GeneralUtility::makeInstance(
			'\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController',
			$TYPO3_CONF_VARS,
			32,
			0,
			TRUE
		);
		$GLOBALS['TSFE']->connectToDB();
		$GLOBALS['TSFE']->fe_user = $userObj;
		$GLOBALS['TSFE']->id = GeneralUtility::_GET('id');
		$GLOBALS['TSFE']->determineId();
		$GLOBALS['TSFE']->getCompressedTCarray();
		$GLOBALS['TSFE']->initTemplate();
		$GLOBALS['TSFE']->getConfigArray();
		$GLOBALS['TSFE']->includeTCA();

		$this->div = GeneralUtility::makeInstance('\In2code\PowermailCond\Utility\Div');
	}
}
$eid = GeneralUtility::makeInstance('In2code\PowermailCond\Utility\Eid\ClearSessionEid', $GLOBALS['TYPO3_CONF_VARS']);
echo $eid->main();