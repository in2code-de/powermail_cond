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
 * Store fields in session which should not be mandatory any more
 *
 * @author Alex Kellner <alexander.kellner@in2code.de>, in2code.de
 * @package TYPO3
 * @subpackage DeRequiredFieldsEid
 */
class DeRequiredFieldsEid {

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
	 * save field in session to be stored for non-mandatory fields
	 *
	 * @return int Field Uid which was disabled
	 */
	public function main() {
		/** @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj */
		$cObj = GeneralUtility::makeInstance('\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');
		$piVars = GeneralUtility::_GP($this->prefixId);
		$formUid = intval($piVars['formUid']);
		$fieldUids = explode(',', $piVars['fieldUids']);
		$conditions = $this->div->getConditionsFromForm($formUid, $cObj);

		foreach ($fieldUids as $fieldUid) {
			// only if this field was defined as targetField in conditions
			if (array_key_exists($fieldUid, $conditions)) {
				// save single value in session
				$this->div->saveValueToSession('', $formUid, $fieldUid, 'deRequiredFields');
			}
		}

		return htmlspecialchars($piVars['fieldUids']);
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

$eid = GeneralUtility::makeInstance('In2code\PowermailCond\Utility\Eid\DeRequiredFieldsEid', $GLOBALS['TYPO3_CONF_VARS']);
echo $eid->main();