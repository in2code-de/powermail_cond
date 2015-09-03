<?php
namespace In2code\PowermailCond\Controller;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Domain\Model\Page;
use In2code\PowermailCond\Domain\Model\ConditionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

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
 * ConditionController
 *
 * @package powermail_cond
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class ConditionController extends ActionController {

	/**
	 * @var \In2code\Powermail\Domain\Repository\FormRepository
	 * @inject
	 */
	protected $formRepository;

	/**
	 * @var \In2code\PowermailCond\Domain\Repository\ConditionRepository
	 * @inject
	 */
	protected $conditionRepository;

	/**
	 * Build Condition for AJAX call
	 *
	 * @return string
	 */
	public function buildConditionAction() {
		$arguments = GeneralUtility::_GP('tx_powermail_pi1');
		/** @var Form $formObject */
		$formObject = $this->formRepository->findByIdentifier($arguments['mail']['form']);
		/** @var Page $page */
		foreach ($formObject->getPages() as $page) {
			/** @var Field $field */
			foreach ($page->getFields() as $field) {
				foreach ($arguments['field'] as $fieldName => $fieldValue) {
					if ($field->getMarker() === $fieldName) {
						$field->setText($fieldValue);
					}
				}
			}
		}
		$conditionsContainer = new ConditionContainer($this->conditionRepository->findByForm($formObject));
		$arguments = $conditionsContainer->applyConditions($formObject, $arguments);
		return json_encode($arguments);
	}
}
