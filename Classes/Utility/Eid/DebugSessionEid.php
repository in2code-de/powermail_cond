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
 * Debug Session values
 *
 * @author Alex Kellner <alexander.kellner@in2code.de>, in2code.de
 * @package TYPO3
 * @subpackage DebugSessionEid
 */
class DebugSessionEid {

	/**
	 * Read values from session - example: 18:braun;17:rot;12:xd;11:fc;
	 *
	 * @return string
	 */
	public function main() {
		if (empty($GLOBALS['BE_USER']->user['uid'])) {
			return 'Please login into backend';
		}

		/* @var $div \In2code\PowermailCond\Utility\Div */
		$div = GeneralUtility::makeInstance('\In2code\PowermailCond\Utility\Div');
		$piVars = GeneralUtility::_GP('tx_powermailcond_pi1');
		if (empty($piVars['formUid'])) {
			return 'tx_powermailcond_pi1[formUid] is missing';
		}

		$sessionForm = $div->getAllSessionValuesFromForm($piVars['formUid']);
		$sessionDeRequiredFields = $div->getAllSessionValuesFromForm($piVars['formUid'], 'deRequiredFields');

		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($sessionForm, 'Values in Session');
		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($sessionDeRequiredFields, 'Disabled Required Fields');
		return '';
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
		$GLOBALS['BE_USER'] = GeneralUtility::makeInstance('\TYPO3\CMS\Core\Authentication\BackendUserAuthentication');
		$GLOBALS['BE_USER']->start();
		$GLOBALS['BE_USER']->backendCheckLogin();
	}
}

$eid = GeneralUtility::makeInstance('In2code\PowermailCond\Utility\Eid\DebugSessionEid', $GLOBALS['TYPO3_CONF_VARS']);
echo $eid->main();