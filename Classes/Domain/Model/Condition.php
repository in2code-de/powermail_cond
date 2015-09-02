<?php
namespace In2code\PowermailCond\Domain\Model;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Domain\Model\Page;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Alex Kellner <alexander.kellner@in2code.de>, in2code.de
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
 * Condition Model
 *
 * @package powermail_cond
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class Condition extends AbstractEntity {

	/**
	 * @var \In2code\Powermail\Domain\Repository\FieldRepository
	 * @inject
	 */
	protected $fieldRepository;

	/**
	 * @var \In2code\Powermail\Domain\Repository\PageRepository
	 * @inject
	 */
	protected $pageRepository;

	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\In2code\PowermailCond\Domain\Model\Rule>
	 */
	protected $rules = NULL;

	/**
	 * @var string
	 */
	protected $targetField = '';

	/**
	 * 0 hide
	 * 1 unhide
	 *
	 * @var int
	 */
	protected $actions = 0;

	/**
	 * @var string
	 */
	protected $filterSelectField = '';

	/**
	 * "OR"
	 * "AND"
	 *
	 * @var string
	 */
	protected $conjunction = '';

	/**
	 * @var \In2code\Powermail\Domain\Model\Form
	 */
	protected $form = '';

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return Condition
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function getRules() {
		return $this->rules;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $rules
	 * @return Condition
	 */
	public function setRules($rules) {
		$this->rules = $rules;
		return $this;
	}

	/**
	 * @return Field|Page|NULL
	 */
	public function getTargetField() {
		$targetField = $this->targetField;
		if (is_numeric($targetField)) {
			return $this->fieldRepository->findByUid((int) $targetField);
		}
		if (stristr($targetField, 'fieldset:')) {
			return $this->pageRepository->findByUid((int) trim($targetField, 'fieldset:'));
		}
		return NULL;
	}

	/**
	 * @param string $targetField
	 * @return Condition
	 */
	public function setTargetField($targetField) {
		$this->targetField = $targetField;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getActions() {
		return $this->actions;
	}

	/**
	 * @param int $actions
	 * @return Condition
	 */
	public function setActions($actions) {
		$this->actions = $actions;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFilterSelectField() {
		return $this->filterSelectField;
	}

	/**
	 * @param string $filterSelectField
	 * @return Condition
	 */
	public function setFilterSelectField($filterSelectField) {
		$this->filterSelectField = $filterSelectField;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getConjunction() {
		return $this->conjunction;
	}

	/**
	 * @param string $conjunction
	 * @return Condition
	 */
	public function setConjunction($conjunction) {
		$this->conjunction = $conjunction;
		return $this;
	}

	/**
	 * @return Form
	 */
	public function getForm() {
		return $this->form;
	}

	/**
	 * @param Form $form
	 * @return Condition
	 */
	public function setForm($form) {
		$this->form = $form;
		return $this;
	}

//	public function applies($form) {
//		foreach ($this->rules as $rule) {
//			if ($rule->applies($form)) {
//				return true;
//			}
//		}
//		return false;
//	}
//	public function applyOnForm($form) {
//		$this->doStuff($form);
//	}
}