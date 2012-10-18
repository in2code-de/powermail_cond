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
require_once(PATH_tslib . 'class.tslib_fe.php');
require_once(PATH_t3lib . 'class.t3lib_page.php');
require_once(t3lib_extMgm::extPath('powermail_cond') . 'Classes/Utility/Div.php');

/**
 * This class tells jQuery which field are allowed in which are not allowed
 *
 * @author	Alex Kellner <alexander.kellner@in2code.de>, in2code.
 * @package	TYPO3
 * @subpackage	tx_powermailcond_ajaxWriteInSession_eid
 */
class Tx_PowermailCond_Utility_EidGetFieldlist extends tslib_pibase {

	/**
	 * Extension Key
	 *
	 * @var string
	 */
	public $extKey = 'powermail_cond';

	/**
	 * Prefix ID for Plugin Vars
	 *
	 * @var string
	 */
	public $prefixId = 'tx_powermailcond_pi1';

	/**
	 * Plugin Vars
	 *
	 * @var array
	 */
	public $piVars;

	/**
	 * Session Vars
	 *
	 * @var array
	 */
	public $session;

	/**
	 * Return list with fields which are not allowed (should be hidden)
	 *
	 * @return	string	$content: 	commaseparated field list (1,2,3)
	 * 								complex list could be:
	 *								filter:12:option1;option2,12,13,fieldset:2:18;19
	 *								explanation - filter select.uid12, hide uid12, hide uid3, hide fieldset uid2 and clear uid18 and uid19
	 */
	public function main() {
		$content = '';
		$this->cObj = $this->getCObj(); // enable TSFE globals
		$conditions = $this->getConditionsFromForm($this->piVars['formUid']); // get related conditions
		$targetFields = $this->div->getStartFields($conditions); // get all startfields in an array
		if ($this->piVars['uid'] > 0 && !in_array($this->piVars['uid'], $targetFields)) { // if current field uid given and this
			return 'nochange';
		}

		foreach ((array) $conditions as $conf) { // one loop for every single target field
			$content .= $this->checkRules($conf) . ','; // add list to content object
		}

		$content = rtrim($content, ','); // remove last ,
		$content = t3lib_div::uniqueList($content); // remove double values

		return $content;
	}

	/**
	 * Preflight function checks the rules if there should be an action (show/hide) or not
	 *
	 * @param	array	$conf: Configuration of current field
	 * @return	boolean true:hide false:show(nothing)
	 */
	public function checkRules($conf) {
		$content = '';
		$do = 0; // start with 0
		if ($conf[0]['conjunction'] == 'AND') {
			$do = 1; // start with 1
		}

		foreach ((array) $conf as $key => $subconf) { // one loop for every rule of current target field

			// special case: hide a field from the beginning
			if ($conf[$key]['actions'] == 1) { // show
				$content .= $this->div->getFieldsFromFieldset($conf[$key]['targetField'], $this->piVars['formUid']) . ',';
			}

			// operations
			$act = 0;
			$startFieldSession = $this->session['field_' . $conf[$key]['startField']];
			if (is_array($startFieldSession)) { // if second level
				$startFieldSession = implode(',', $startFieldSession); // get all values in a commaseparated list
			}
			switch ($conf[$key]['ops']) {
				case 0: // "not empty"
					if ($startFieldSession != '') { // if start field value in session is not empty
						$act = 1;
						$tmp_conf = $conf[$key];
					}
					break;

				case 1: // "empty"
					if ($startFieldSession == '') { // if start field value in session is empty
						$act = 1;
						$tmp_conf = $conf[$key];
					}
					break;

				case 2: // "contains"
					if (stristr($startFieldSession, $conf[$key]['condstring'])) { // if start field value in session contains condstring
						$act = 1;
						$tmp_conf = $conf[$key];
					}
					break;

				case 3: // "not contains"
					if (!stristr($startFieldSession, $conf[$key]['condstring'])) { // if start field value in session contains not condstring
						$act = 1;
						$tmp_conf = $conf[$key];
					}
					break;

				case 4: // "is"
					if ($startFieldSession === $conf[$key]['condstring']) { // if start field value in session === condstring
						$act = 1;
						$tmp_conf = $conf[$key];
					}
					break;

				case 5: // "is not"
					if ($startFieldSession !== $conf[$key]['condstring']) { // if start field value in session is not condstring
						$act = 1;
						$tmp_conf = $conf[$key];
					}
					break;

				case 6: // "is greater than"
					if (intval($startFieldSession) > intval($conf[$key]['condstring'])) { // if start field value in session is greater than condstring
						$act = 1;
						$tmp_conf = $conf[$key];
					}
					break;

				case 7: // "is less than"
					if (intval($startFieldSession) < intval($conf[$key]['condstring'])) { // if start field value in session is greater than condstring
						$act = 1;
						$tmp_conf = $conf[$key];
					}
					break;

				case 8: // "contains value from field"
					$comparisonFieldSession = $this->session['field_' . $conf[$key]['equalField']]; // get comparisonfield value from session
					if (stristr($comparisonFieldSession, $startFieldSession)) {
						$act = 1;
						$tmp_conf = $conf[$key];
					}
					break;

				case 9: // "contains not value from field"
					$comparisonFieldSession = $this->session['field_' . $conf[$key]['equalField']]; // get comparisonfield value from session
					if (!stristr($comparisonFieldSession, $startFieldSession)) {
						$act = 1;
						$tmp_conf = $conf[$key];
					}
					break;
			}

			$do = $this->setDo(($act ? 1 : 0), $do, $conf[$key]['conjunction']); // $do = 1;
		}

		$content = $this->doAction($do, $content, $tmp_conf); // add new field if needed
		return rtrim($content, ','); // return commaseparated list
	}

	/**
	 * This function return current field uid if this field should be hidden (and removes value from session)
	 *
	 * @param	boolean	$do: If something should be done or not
	 * @param	string	$content: List with all fields which should be hidden
	 * @param	array	$conf: Configuration of current field
	 * @return	string list
	 */
	public function doAction($do, $content, $conf) {
		if (!$do) {
			return $content;
		}

		switch ($conf['actions']) {
			case 0: // hide
				$this->div->saveValueToSession('', $this->piVars['formUid'], $conf['targetField']); // remove value from session of this field
				$content .= $this->div->getFieldsFromFieldset($conf['targetField'], $this->piVars['formUid']) . ','; // hide this field
				break;

			case 1: // show
				$content = t3lib_div::rmFromList($this->div->getFieldsFromFieldset($conf['targetField'], $this->piVars['formUid']), $content); // remove from hidelist (show this field)
				break;

			case 2: // filter from selectbox
				$content .= 'filter:' . $conf['targetField'] . ':' . str_replace(',', ';', $conf['filterSelectField']);
				break;
		}

		return $content;
	}

	/**
	 * Set a value to 0 or 1 depending on previous value and on conjunction (AND/OR)
	 *
	 * @param	boolean	$newStatus: New status
	 * @param	boolean	$oldStatus: Old status
	 * @param	string	$conjunction: AND or OR
	 * @return	boolean
	 */
	private function setDo($newStatus, $oldStatus = 0, $conjunction = 'OR') {
		//t3lib_div::debug(array($newStatus, $oldStatus), $conjunction);
		if ($conjunction == 'OR') {
			if ($newStatus || $oldStatus) {
				return 1;
			} else {
				return 0;
			}
		} elseif ($conjunction == 'AND') {
			if ($newStatus && $oldStatus) {
				return 1;
			} else {
				return 0;
			}
		}
	}

	/**
	 * get condition as array from current page
	 *
	 * @return	array	$arr: Array with all conditions of the current page
	 */
	private function getConditionsFromForm($formUid) {
		$arr = array();
		$select = '
				tx_powermailcond_domain_model_condition.targetField, tx_powermailcond_domain_model_condition.actions, tx_powermailcond_domain_model_condition.conjunction,
				tx_powermailcond_domain_model_condition.filterSelectField, tx_powermailcond_domain_model_rule.startField, tx_powermailcond_domain_model_rule.ops,
				tx_powermailcond_domain_model_rule.condstring, tx_powermailcond_domain_model_rule.equalField
		';
		$from = '
			tx_powermailcond_domain_model_condition
			LEFT JOIN tx_powermailcond_domain_model_rule ON tx_powermailcond_domain_model_condition.uid = tx_powermailcond_domain_model_rule.conditions
		';
		$where = (intval($formUid) ? 'tx_powermailcond_domain_model_condition.form = ' . intval($formUid) : '1') . $this->cObj->enableFields('tx_powermailcond_domain_model_condition') . $this->cObj->enableFields('tx_powermailcond_domain_model_rule');
		$groupBy = 'tx_powermailcond_domain_model_rule.uid';
		$orderBy = '';
		$limit = 1000;
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
		if ($res) { // If there is a result
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) { // One loop for every rule on current page
				$arr[$row['targetField']][] = $row;
			}

			return $arr;
		}
	}

	/**
	 * Initialize cObj and TSFE Globals
	 *
	 * @return	object	cObj
	 */
	private function getCObj() {
		$this->piVars = t3lib_div::_GET($this->prefixId);
		$userObj = tslib_eidtools::initFeUser();
		$temp_TSFEclassName = t3lib_div::makeInstance('tslib_fe');
		$GLOBALS['TSFE'] = new $temp_TSFEclassName($TYPO3_CONF_VARS, 0, 0, true);
		$GLOBALS['TSFE']->connectToDB();
		$GLOBALS['TSFE']->fe_user = $userObj;
		$GLOBALS['TSFE']->id = t3lib_div::_GET('id');
		$GLOBALS['TSFE']->determineId();
		$GLOBALS['TSFE']->getCompressedTCarray();
		$GLOBALS['TSFE']->initTemplate();
		$GLOBALS['TSFE']->getConfigArray();
		$GLOBALS['TSFE']->includeTCA();

		$this->div = t3lib_div::makeInstance('Tx_PowermailCond_Utility_Div'); // Create new instance for div class
		$this->session = $this->div->getAllSessionValuesFromForm($this->piVars['formUid']);

		return t3lib_div::makeInstance('tslib_cObj');
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/powermail_cond/lib/class.tx_powermailcond_ajaxFieldList_eid.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/powermail_cond/lib/class.tx_powermailcond_ajaxFieldList_eid.php']);
}

$SOBE = t3lib_div::makeInstance('Tx_PowermailCond_Utility_EidGetFieldlist'); // make instance
echo $SOBE->main(); // print content
?>