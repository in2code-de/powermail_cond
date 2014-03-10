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
	 * Save value to session and respect old entries
	 *
	 * @param string $value		Value to store
	 * @param int $form			Form uid
	 * @param int $field		Field uid
	 * @return void
	 */
	public function saveValueToSession($value, $form, $field) {
		$form = intval($form);

		// get old session
		$oldArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey);

		// merge old and new
		$array = array(
			'form_' . $form => array(
				'field_' . $field => $value
			)
		);
		$array['form_' . $form] = array_merge((array) $oldArray['form_' . $form], (array) $array['form_' . $form]);

		// save new array
		// Generate Session with piVars array
		$GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey, $array);
		$GLOBALS['TSFE']->storeSessionData();
	}

	/**
	 * Return all values from the session (could be used for debugging, etc..)
	 *
	 * @param int $form			Form Uid
	 * @return array $array		with session values
	 */
	public function getAllSessionValuesFromForm($form = NULL) {
		// get current stored values from session
		$array = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey);

		if (isset($array['form_' . $form])) {
			return $array['form_' . $form];
		}
		return $array;
	}

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
	 * @return void
	 */
	public function cleanfullSession($formUid = NULL) {
		if (intval($formUid) > 0) {
			$array = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey);
			$array['form_' . $formUid] = array();
		} else {
			$array = array();
		}
		$GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey, $array);
		$GLOBALS['TSFE']->storeSessionData();
	}
}