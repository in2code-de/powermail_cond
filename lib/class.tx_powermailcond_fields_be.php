<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Alex Kellner <alexander.kellner@in2code.de>, in2code.
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

/**
 * Class/Function which manipulates the item-array for table/field tx_powermailcond_rules_fieldname.
 *
 * @author	Alex Kellner <alexander.kellner@in2code.de>, in2code.
 * @package	TYPO3
 * @subpackage	tx_powermailcond_fields_be
 */
class tx_powermailcond_fields_be {
	
	/**
	 * show all fields in the backend
	 *
	 * @param	array	$params: Params
	 * @param	object	$pObj: Parent Object
	 * @return	void
	 */
	public function fieldname(&$params, $pObj)	{
		$where = '1';
		if (isset($params['config']['itemsProcFuncValue'])) { // additional where clause
			$where = 'formtype IN (' . $params['config']['itemsProcFuncValue'] . ')';
		}
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			$select = 'uid, title',
			$from = 'tx_powermail_fields',
			$where .= ' AND pid = ' . intval($params['row']['pid']) . ' AND hidden = 0 AND deleted = 0',
			$groupBy = '',
			$orderBy = 'sorting',
			$limit = ''
		);
		if ($res) {
			$params['items'][] = array('powermail Fields', '--div--');
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$params['items'][] = array($pObj->sL($row['title']) . ' (' . $row['uid'] . ')', $row['uid']);
			}
		}
		
		if (isset($params['config']['itemsProcFunc_addFieldsets'])) { // add fieldsets to selection
			$params['items'] = array_merge((array) $params['items'], $this->getFieldsets($params['row']['pid'])); // add some fieldsets
		}
	}
	
	/**
	 * give me all fieldsets in an array
	 *
	 * @param	integer	$pid: Page ID
	 * @return	array	$arr: all fieldsets with its name and the fieldset uid
	 */
	public function getFieldsets($pid) {
		$arr = array();
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			$select = 'uid, title',
			$from = 'tx_powermail_fieldsets',
			$where .= 'pid = ' . intval($pid) . ' AND hidden = 0 AND deleted = 0',
			$groupBy = '',
			$orderBy = 'sorting',
			$limit = ''
		);
		if ($res) {
			$arr[] = array('powermail Fieldsets', '--div--');
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$arr[] = array($row['title'] . ' (' . $row['uid'] . ')', 'fieldset:' . $row['uid']);
			}
		}
		return $arr;
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
		$gParams = t3lib_div::_GET('edit');
		$gParams2 = $gParams['tx_powermailcond_conditions'];
		foreach ((array) $gParams2 as $uid => $actions) {
			$thisConditionsUid = $uid;
		}
		$targetField = $pObj->cachedTSconfig['tx_powermailcond_conditions:' . $thisConditionsUid]['_THIS_ROW']['targetField'];
		
		// Read values from powermail 
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			$select = 'flexform',
			$from = 'tx_powermail_fields',
			$where = 'uid = ' . intval($targetField),
			$groupBy = '',
			$orderBy = '',
			$limit = '1'
		);
		if ($res) {
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		}
		
		// Change xml to a readable format
		$arr = (array) t3lib_div::xml2array($row['flexform']);
		$optionlist = $arr['data']['sDEF']['lDEF']['options']['vDEF'];
		$options = t3lib_div::trimExplode("\n", $optionlist, 1);
		
		// write params
		foreach ((array) $options as $option) {
			$params['items'][] = array(htmlspecialchars($option), htmlspecialchars($option));
		}
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/powermail_cond/class.tx_powermailcond_fields_be.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/powermail_cond/class.tx_powermailcond_fields_be.php']);
}

?>