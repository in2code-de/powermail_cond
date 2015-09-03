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

use In2code\PowermailCond\Utility\ArrayUtility;
use TYPO3\CMS\Backend\Form\FormEngine;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * List powermail fields in Backend for powermail_cond rules
 *
 * @package powermail_cond
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class GetPowermailFields {

	/**
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $databaseConnection = NULL;

	/**
	 * @var array
	 */
	protected $params = array();

	/**
	 * @var FormEngine
	 */
	protected $formEngine = NULL;

	/**
	 * @var int
	 */
	protected $formUid = 0;

	/**
	 * @var array
	 */
	protected $defaultFieldTypes = array(
		'input',
		'textarea',
		'select',
		'radio',
		'check'
	);

	/**
	 * show all fields in the backend
	 *
	 * @param array $params
	 * @param FormEngine $formEngine
	 * @return void
	 */
	public function getFields(array &$params, FormEngine $formEngine) {
		$this->initialize($params, $formEngine);
		$this->addFieldsToParams();
		$this->addFieldsetsToParams();
	}

	/**
	 * Add fields to params array
	 *
	 * @return void
	 */
	protected function addFieldsToParams() {
		$fields = $this->getFieldsFromForm();
		$this->params['items'][] = array('powermail Fields', '--div--');
		foreach ($fields as $properties) {
			$this->params['items'][] = array(
				$this->getLabelFromFieldProperties($properties),
				$properties['uid']
			);
		}
	}

	/**
	 * Add fieldsets to Params
	 *
	 * @return void
	 */
	protected function addFieldsetsToParams() {
		if (empty($this->params['config']['itemsProcFunc_addFieldsets'])) {
			return;
		}
		$fieldsets = $this->getFieldsetsFromForm();
		$this->params['items'][] = array('powermail Fieldsets', '--div--');
		foreach ($fieldsets as $properties) {
			$this->params['items'][] = array(
				$properties['title'] . ' (' . $properties['uid'] . ')',
				'fieldset:' . $properties['uid']
			);
		}
	}

	/**
	 * Get fields
	 *
	 * @return array
	 */
	protected function getFieldsFromForm() {
		$fields = array();
		$select = 'f.uid, f.title, f.marker';
		$from = '
			tx_powermail_domain_model_fields f
			left join tx_powermail_domain_model_pages p on f.pages = p.uid
			left join tx_powermail_domain_model_forms fo on p.forms = fo.uid
		';
		$where = 'f.hidden = 0 and f.deleted = 0 and f.type in (' . $this->getDefaultFieldTypesForQuery() . ')';
		if ($this->getFormUid() > 0) {
			$where .= ' and fo.uid = ' . $this->getFormUid();
		}
		$groupBy = '';
		$orderBy = 'f.sorting';
		$limit = 10000;
		$res = $this->databaseConnection->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
		if ($res) {
			while (($row = $this->databaseConnection->sql_fetch_assoc($res))) {
				$fields[] = $row;
			}
		}
		return $fields;
	}

	/**
	 * give me all fieldsets in an array
	 *
	 * @return array
	 */
	protected function getFieldsetsFromForm() {
		$fieldsets = array();
		$select = 'uid, title';
		$from = 'tx_powermail_domain_model_pages';
		$where = 'forms = ' . $this->getFormUid() . ' AND hidden = 0 AND deleted = 0';
		$groupBy = '';
		$orderBy = 'sorting';
		$limit = '';
		$res = $this->databaseConnection->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
		if ($res) {
			while (($row = $this->databaseConnection->sql_fetch_assoc($res))) {
				$fieldsets[] = $row;
			}
		}
		return $fieldsets;
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
		$where = 'uid = ' . (int) $conditionUid . ' AND hidden = 0 AND deleted = 0';
		$groupBy = '';
		$orderBy = '';
		$limit = 1;
		$res = $this->databaseConnection->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
		if ($res) {
			$row = $this->databaseConnection->sql_fetch_assoc($res);
			return (int) $row['form'];
		}
		return 0;
	}

	/**
	 * Build a label
	 *
	 * @param array $properties
	 * @return string
	 */
	protected function getLabelFromFieldProperties(array $properties) {
		return $this->formEngine->sL($properties['title']) . ', {' . $properties['marker'] . '}, uid' . $properties['uid'];
	}

	/**
	 * @param array $params
	 * @param FormEngine $formEngine
	 * @return void
	 */
	protected function initialize(array &$params, FormEngine $formEngine) {
		$this->databaseConnection = $GLOBALS['TYPO3_DB'];
		$this->params = &$params;
		$this->formEngine = $formEngine;
		$this
			->setFormUid()
			->setDefaultFieldTypes();
	}

	/**
	 * @return GetPowermailFields
	 */
	public function setFormUid() {
		$formUid = (int) $this->params['row']['form'];
		if (!empty($this->params['row']['conditions'])) {
			$formUid = $this->getFormUidFromCondition($this->params['row']['conditions']);
		}
		$this->formUid = $formUid;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getFormUid() {
		return $this->formUid;
	}

	/**
	 * @return GetPowermailFields
	 */
	public function setDefaultFieldTypes() {
		if (!empty($this->params['config']['itemsProcFuncValue'])) {
			$this->defaultFieldTypes = GeneralUtility::trimExplode(',', $this->params['config']['itemsProcFuncValue'], TRUE);
		}
		return $this;
	}

	/**
	 * @return array
	 */
	public function getDefaultFieldTypes() {
		return $this->defaultFieldTypes;
	}

	/**
	 * @return string
	 */
	public function getDefaultFieldTypesForQuery() {
		$fieldTypes = $this->getDefaultFieldTypes();
		return ArrayUtility::getQuotedList($fieldTypes);
	}
}
