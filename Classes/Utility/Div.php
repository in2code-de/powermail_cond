<?php
namespace In2code\PowermailCond\Utility;

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
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class Div {

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
	 * Get Startfields
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

	/**
	 * get condition as array from current page
	 *
	 * @param int $formUid
	 * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj
	 * @return array $arr: Array with all conditions of the current page
	 */
	public function getConditionsFromForm($formUid, $cObj) {
		$arr = array();
		$select = '
				tx_powermailcond_domain_model_condition.targetField, tx_powermailcond_domain_model_condition.actions,
				tx_powermailcond_domain_model_condition.conjunction,
				tx_powermailcond_domain_model_condition.filterSelectField, tx_powermailcond_domain_model_rule.startField,
				tx_powermailcond_domain_model_rule.ops,
				tx_powermailcond_domain_model_rule.condstring, tx_powermailcond_domain_model_rule.equalField
		';
		$from = '
			tx_powermailcond_domain_model_condition
			LEFT JOIN tx_powermailcond_domain_model_rule ON
			tx_powermailcond_domain_model_condition.uid = tx_powermailcond_domain_model_rule.conditions
		';
		$where = (intval($formUid) ? 'tx_powermailcond_domain_model_condition.form = ' . intval($formUid) : '1');
		$where .= $cObj->enableFields('tx_powermailcond_domain_model_condition');
		$where .= $cObj->enableFields('tx_powermailcond_domain_model_rule');
		$groupBy = 'tx_powermailcond_domain_model_rule.uid';
		$orderBy = '';
		$limit = 1000;
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
		if ($res) {
			while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
				$arr[$row['targetField']][] = $row;
			}
		}
		return $arr;
	}

	/**
	 * Get all fields in a commaseparated list from a fieldset uid
	 *
	 * @param integer $uid: Fieldset UID
	 * @param integer $formUid: UID of related form
	 * @return string $list: Commaseparated List with field uids
	 */
	public function getFieldsFromFieldset($uid, $formUid) {
		// if this uid don't contains fs (for fs123)
		if (is_numeric($uid)) {
			return $uid;
		}

		$select = 'tx_powermail_domain_model_fields.uid';
		$from = '
			tx_powermail_domain_model_pages
			LEFT JOIN tx_powermail_domain_model_fields ON tx_powermail_domain_model_pages.uid = tx_powermail_domain_model_fields.pages
		';
		$where = 'tx_powermail_domain_model_pages.uid = ' . intval(str_replace('fieldset:', '', $uid));
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery ($select, $from, $where, '', '', 1000);
		if ($res) {
			$uids = '';
			while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
				$uids .= $row['uid'] . ';';
				// remove value from session of this field
				$this->saveValueToSession('', $formUid, $row['uid']);
			}
		}

		if (!isset($uids)) {
			return $uid;
		}

		// return without last ;
		return $uid . ':' . substr($uids, 0, -1);
	}

	/**
	 * Get all fields in a commaseparated list from a fieldset uid
	 *
	 * @param int $formUid UID of related form
	 * @param string $prefix Prefix for session
	 * @return void
	 */
	public function cleanfullSession($formUid = NULL, $prefix = 'fieldSession') {
		if (intval($formUid) > 0) {
			$array = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey);
			$array[$prefix]['form_' . $formUid] = array();
		} else {
			$array[$prefix] = array();
		}
		$GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey, $array);
		$GLOBALS['TSFE']->storeSessionData();
	}

	/**
	 * Save value to session and respect old entries
	 *
	 * @param string $value Value to store
	 * @param int $form Form uid
	 * @param int $field Field uid
	 * @param string $prefix Prefix for session
	 * @return void
	 */
	public function saveValueToSession($value, $form, $field, $prefix = 'fieldSession') {
		$form = intval($form);

		// get old session
		$session = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey);
		if (isset($session[$prefix]['form_' . $form])) {
			$oldArray = $session[$prefix]['form_' . $form];
		} else {
			$oldArray = array();
		}

		// merge old and new
		$array = array(
			'field_' . $field => $value
		);
		$array[$prefix]['form_' . $form] = array_merge($oldArray, $array);

		// save new array
		$GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey, $array);
		$GLOBALS['TSFE']->storeSessionData();
	}

	/**
	 * Save value to session and respect old entries
	 *
	 * @param int $form Form uid
	 * @param int $field Field uid
	 * @param string $prefix Prefix for session
	 * @return void
	 */
	public function removeValueFromSession($form, $field, $prefix = 'fieldSession') {
		$form = intval($form);
		$field = intval($field);

		// get old session
		$session = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey);
		if (isset($session[$prefix]['form_' . $form]['field_' . $field])) {
			unset($session[$prefix]['form_' . $form]['field_' . $field]);

			// save again
			$GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey, $session);
			$GLOBALS['TSFE']->storeSessionData();
		}
	}

	/**
	 * Return all values from the session (could be used for debugging, etc..)
	 *
	 * @param int $form Form Uid
	 * @param string $prefix Prefix for session
	 * @return array $array with session values
	 */
	public function getAllSessionValuesFromForm($form = NULL, $prefix = 'fieldSession') {
		// get current stored values from session
		$array = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey);

		if (isset($array[$prefix]['form_' . $form])) {
			return $array[$prefix]['form_' . $form];
		}
		return $array[$prefix];
	}
}