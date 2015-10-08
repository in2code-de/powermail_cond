<?php
namespace In2code\PowermailCond\UserFunc;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 in2code.de
 *  Alex Kellner <alexander.kellner@in2code.de>,
 *  Oliver Eglseder <oliver.eglseder@in2code.de>
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

use TYPO3\CMS\Backend\Form\FormEngine;

/**
 * Get powermail forms that have no related condition containers
 *
 * @package powermail_cond
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class GetPowermailFormsWithoutConditionRelation {

	/**
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $databaseConnection = NULL;

	/**
	 * @var array
	 */
	protected $params = array();

	/**
	 * @var int
	 */
	protected $currentFormUid = 0;

	/**
	 * remove forms that are already related to a condition container
	 *
	 * @param array $params
	 * @return void
	 */
	public function filterForms(array &$params) {
		$this->initialize($params);
		foreach ((array) $this->params['items'] as $key => $form) {
			if ($this->hasFormRelatedConditionContainers((int) $form[1]) && (int) $form[1] !== $this->currentFormUid) {
				unset($this->params['items'][$key]);
			}
		}
	}

	/**
	 * @param int $formUid
	 * @return bool
	 */
	protected function hasFormRelatedConditionContainers($formUid) {
		$select = 'cc.uid';
		$from = 'tx_powermailcond_domain_model_conditioncontainer cc';
		$where = 'cc.form = ' . (int) $formUid . ' and cc.deleted = 0';
		$res = $this->databaseConnection->exec_SELECTquery($select, $from, $where);
		if ($res) {
			while (($row = $this->databaseConnection->sql_fetch_assoc($res))) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * @param array $params
	 * @return void
	 */
	protected function initialize(array &$params) {
		$this->databaseConnection = $GLOBALS['TYPO3_DB'];
		$this->params = &$params;
		$this->currentFormUid = (int) $this->params['row']['form'];
	}
}
