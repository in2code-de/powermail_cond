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
 * This class tells jQuery which field are allowed in which are not allowed
 *
 * @author	Alex Kellner <alexander.kellner@in2code.de>, in2code.
 * @package	TYPO3
 * @subpackage	Tx_PowermailCond_Utility_EidGetFieldlist
 */
class Tx_PowermailCond_Utility_EidGetFieldlist {

	/**
	 * Prefix ID for Plugin Vars
	 *
	 * @var string
	 */
	public $prefixId = 'tx_powermailcond_pi1';

	/**
	 * @var tslib_cObj
	 */
	protected $cObj;

	/**
	 * @var Tx_PowermailCond_Utility_Div
	 */
	protected $div;

	/**
	 * Generates the output
	 *
	 * @return string		from action
	 */
	public function main() {
		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
		$conditions = $this->div->getConditionsFromForm($this->piVars['formUid'], $this->cObj);
		$targetFields = $this->div->getStartFields($conditions);
		if ($this->piVars['uid'] > 0 && !in_array($this->piVars['uid'], $targetFields)) {
			return 'nochange';
		}

		$content = '';
		foreach ((array) $conditions as $conf) {
			$content .= $this->checkRules($conf) . ',';
		}

		// remove last ,
		$content = rtrim($content, ',');
		// remove double values
		$content = t3lib_div::uniqueList($content);

		return $content;
	}

	/**
	 * Preflight function checks the rules
	 * 		if there should be an action (show/hide) or not
	 *
	 * @param	array	$conf: Configuration of current field
	 * @return	boolean true:hide false:show(nothing)
	 */
	public function checkRules($conf) {
		// start with 0
		$do = 0;
		$content = '';
		$tmpConf = array();
		if ($conf[0]['conjunction'] == 'AND') {
			// start with 1
			$do = 1;
		}

		// one loop for every rule of current target field
		foreach ((array) $conf as $key => $subconf) {
			$subconf = NULL;

			// special case: hide a field from the beginning
			if ($conf[$key]['actions'] == 1) {
				// show
				$content .= $this->div->getFieldsFromFieldset($conf[$key]['targetField'], $this->piVars['formUid']) . ',';
			}

			// operations
			$act = 0;
			$startFieldSession = $this->session['field_' . $conf[$key]['startField']];
			// if second level
			if (is_array($startFieldSession)) {
				// get all values in a commaseparated list
				$startFieldSession = implode(',', $startFieldSession);
			}
			switch ($conf[$key]['ops']) {
				// "not empty"
				case 0:
					// if start field value in session is not empty
					if ($startFieldSession != '') {
						$act = 1;
						$tmpConf = $conf[$key];
					}
					break;

				// "empty"
				case 1:
					// if start field value in session is empty
					if ($startFieldSession == '') {
						$act = 1;
						$tmpConf = $conf[$key];
					}
					break;

				// "contains"
				case 2:
					// if start field value in session contains condstring
					if (stristr($startFieldSession, $conf[$key]['condstring'])) {
						$act = 1;
						$tmpConf = $conf[$key];
					}
					break;

				// "not contains"
				case 3:
					// if start field value in session contains not condstring
					if (!stristr($startFieldSession, $conf[$key]['condstring'])) {
						$act = 1;
						$tmpConf = $conf[$key];
					}
					break;

				// "is"
				case 4:
					// if start field value in session === condstring
					if ($startFieldSession === $conf[$key]['condstring']) {
						$act = 1;
						$tmpConf = $conf[$key];
					}
					break;

				// "is not"
				case 5:
					// if start field value in session is not condstring
					if ($startFieldSession !== $conf[$key]['condstring']) {
						$act = 1;
						$tmpConf = $conf[$key];
					}
					break;

				// "is greater than"
				case 6:
					// if start field value in session is greater than condstring
					if (intval($startFieldSession) > intval($conf[$key]['condstring'])) {
						$act = 1;
						$tmpConf = $conf[$key];
					}
					break;

				// "is less than"
				case 7:
					// if start field value in session is greater than condstring
					if (intval($startFieldSession) < intval($conf[$key]['condstring'])) {
						$act = 1;
						$tmpConf = $conf[$key];
					}
					break;

				// "contains value from field"
				case 8:
					// get comparisonfield value from session
					$comparisonFieldSession = $this->session['field_' . $conf[$key]['equalField']];
					if (stristr($comparisonFieldSession, $startFieldSession)) {
						$act = 1;
						$tmpConf = $conf[$key];
					}
					break;

				// "contains not value from field"
				case 9:
					// get comparisonfield value from session
					$comparisonFieldSession = $this->session['field_' . $conf[$key]['equalField']];
					if (!stristr($comparisonFieldSession, $startFieldSession)) {
						$act = 1;
						$tmpConf = $conf[$key];
					}
					break;

				default:
			}

			// $do = 1;
			$do = $this->setDo(($act ? TRUE : FALSE), $do, $conf[$key]['conjunction']);
		}

		// add new field if needed
		$content = $this->doAction($do, $content, $tmpConf);
		// return commaseparated list
		return rtrim($content, ',');
	}

	/**
	 * Set a value to 0 or 1 depending on previous value and on conjunction (AND/OR)
	 *
	 * @param boolean $newStatus: New status
	 * @param boolean $oldStatus: Old status
	 * @param string $conjunction: AND or OR
	 * @return boolean
	 */
	private function setDo($newStatus, $oldStatus = FALSE, $conjunction = 'OR') {
		if ($conjunction == 'OR') {
			if ($newStatus || $oldStatus) {
				return TRUE;
			} else {
				return FALSE;
			}
		} elseif ($conjunction == 'AND') {
			if ($newStatus && $oldStatus) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
		return FALSE;
	}

	/**
	 * This function return current field uid
	 * 		if this field should be hidden (and removes value from session)
	 *
	 * @param boolean $do: If something should be done or not
	 * @param string $content: List with all fields which should be hidden
	 * @param array $conf: Configuration of current field
	 * @return string list
	 */
	public function doAction($do, $content, $conf) {
		if (!$do) {
			return $content;
		}

		switch ($conf['actions']) {
			// hide
			case 0:
				// remove value from session of this field
				$this->div->saveValueToSession('', $this->piVars['formUid'], $conf['targetField']);
				// hide this field
				$content .= $this->div->getFieldsFromFieldset($conf['targetField'], $this->piVars['formUid']) . ',';
				break;

			// show
			case 1:
				// remove from hidelist (show this field)
				$content = t3lib_div::rmFromList($this->div->getFieldsFromFieldset($conf['targetField'], $this->piVars['formUid']), $content);
				break;

			// filter from selectbox
			case 2:
				$content .= 'filter:' . $conf['targetField'] . ':' . str_replace(',', ';', $conf['filterSelectField']);
				break;

			default:
		}

		return $content;
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

		$this->piVars = t3lib_div::_GET($this->prefixId);
		$this->div = t3lib_div::makeInstance('Tx_PowermailCond_Utility_Div');
		$this->session = $this->div->getAllSessionValuesFromForm($this->piVars['formUid']);
	}
}

$eid = t3lib_div::makeInstance('Tx_PowermailCond_Utility_EidGetFieldlist', $GLOBALS['TYPO3_CONF_VARS']);
echo $eid->main();