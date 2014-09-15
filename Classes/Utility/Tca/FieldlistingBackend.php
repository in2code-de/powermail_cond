<?php
namespace In2code\PowermailCond\Utility\Tca;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * List powermail fields in Backend for powermail_cond rules
 *
 * @package powermail_cond
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class FieldlistingBackend {

	/**
	 * show all fields in the backend
	 *
	 * @param	array	$params: Params
	 * @param	object	$pObj: Parent Object
	 * @return	void
	 */
	public function getFieldname(&$params, $pObj) {
		// settings
		$formUid = intval($params['row']['form']);
		if (!empty($params['row']['conditions'])) {
			$formUid = $this->getFormUidFromCondition($params['row']['conditions']);
		}

		// query
		$select = 'tx_powermail_domain_model_fields.uid, tx_powermail_domain_model_fields.title,
			tx_powermail_domain_model_fields.marker';
		$from = '
			tx_powermail_domain_model_fields
			left join tx_powermail_domain_model_pages on tx_powermail_domain_model_fields.pages = tx_powermail_domain_model_pages.uid
			left join tx_powermail_domain_model_forms on tx_powermail_domain_model_pages.forms = tx_powermail_domain_model_forms.uid
		';
		$where = 'tx_powermail_domain_model_fields.hidden = 0 AND tx_powermail_domain_model_fields.deleted = 0';
		// we want only some fields for starting fields
		if (isset($params['config']['itemsProcFuncValue'])) {
			$where .= ' and tx_powermail_domain_model_fields.type in ("input", "textarea", "select", "radio", "check")';
		}
		if ($formUid > 0) {
			$where .= ' AND tx_powermail_domain_model_forms.uid = ' . $formUid;
		}
		$groupBy = '';
		$orderBy = 'tx_powermail_domain_model_fields.sorting';
		$limit = 10000;
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
		if ($res) {
			// Title for optgroup
			$params['items'][] = array('powermail Fields', '--div--');

			while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
				$params['items'][] = array(
					$pObj->sL($row['title']) . ', {' . $row['marker'] . '}, uid' . $row['uid'],
					$row['uid']
				);
			}
		}

		// add fieldsets to selection
		if (isset($params['config']['itemsProcFunc_addFieldsets'])) {
			// add some fieldsets
			$params['items'] = array_merge((array) $params['items'], $this->getFieldsets($formUid));
		}
	}

	/**
	 * give me all fieldsets in an array
	 *
	 * @param int $formUid			Form Uid
	 * @return	array	$arr: all fieldsets with its name and the fieldset uid
	 */
	protected function getFieldsets($formUid) {
		$arr = array();
		$select = 'uid, title';
		$from = 'tx_powermail_domain_model_pages';
		$where = 'forms = ' . intval($formUid) . ' AND hidden = 0 AND deleted = 0';
		$groupBy = '';
		$orderBy = 'sorting';
		$limit = '';
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
		if ($res) {
			$arr[] = array('powermail Fieldsets', '--div--');
			while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
				$arr[] = array($row['title'] . ' (' . $row['uid'] . ')', 'fieldset:' . $row['uid']);
			}
		}
		return $arr;
	}

	/**
	 * Get Form Uid from Rule
	 *
	 * @param int $conditionUid
	 * @return int formUid
	 */
	protected function getFormUidFromCondition($conditionUid) {
		$select = 'form';
		$from = 'tx_powermailcond_domain_model_condition';
		$where = 'uid = ' . intval($conditionUid) . ' AND hidden = 0 AND deleted = 0';
		$groupBy = '';
		$orderBy = '';
		$limit = 1;
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
		if ($res) {
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			return $row['form'];
		}
		return 0;
	}

	/**
	 * List values of a powermail selectorbox
	 *
	 * @param	array	$params: Params
	 * @param	object	$pObj: Parent Object
	 * @return	void
	 */
	public function valuesFromPowermailSelectbox(&$params, $pObj) {
		// Get targetField UID
		$gParams = GeneralUtility::_GET('edit');
		$gParams2 = $gParams['tx_powermailcond_domain_model_condition'];
		$thisConditionsUid = 0;
		foreach ((array) $gParams2 as $uid => $actions) {
			unset($actions);
			$thisConditionsUid = $uid;
		}
		$key = 'tx_powermailcond_domain_model_condition:' . $thisConditionsUid;
		$targetField = $pObj->cachedTSconfig[$key]['_THIS_ROW']['targetField'];

		// Read values from powermail
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			$select = 'settings',
			$from = 'tx_powermail_domain_model_fields',
			$where = 'uid = ' . intval($targetField),
			$groupBy = '',
			$orderBy = '',
			$limit = '1'
		);
		if ($res) {
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		}
		$options = GeneralUtility::trimExplode("\n", $row['settings'], 1);

		// write params
		foreach ((array) $options as $option) {
			$params['items'][] = array(
				htmlspecialchars($option),
				htmlspecialchars($option)
			);
		}
	}
}