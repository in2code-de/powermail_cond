<?php
namespace In2code\PowermailCond\Domain\Validator;

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

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Domain\Model\Page;
use In2code\Powermail\Domain\Validator\InputValidator;
use In2code\Powermail\Utility\ConfigurationUtility;
use In2code\PowermailCond\Domain\Model\Condition;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;

/**
 * Class ConditionAwareValidator
 */
class ConditionAwareValidator extends InputValidator {

	/**
	 * Validate a single field
	 *
	 * @param Field $field
	 * @param mixed $value
	 * @return void
	 */
	protected function isValidField(Field $field, $value) {

		/** @var FrontendUserAuthentication $feUser */
		$feUser = $GLOBALS['TSFE']->fe_user;
		$arguments = $feUser->getSessionData('tx_powermail_cond');
		$fieldMarker = $field->getMarker();


		if (ConfigurationUtility::isReplaceIrreWithElementBrowserActive()) {
			$pages = $field->getPages();
			/** @var Form $form */
			foreach ($pages->getForms() as $form) {
				/** @var Page $page */
				foreach ($form->getPages() as $page) {
					/** @var Field $field */
					foreach ($page->getFields() as $field) {
						if (!empty($arguments[$form->getUid()][$page->getUid()][$fieldMarker][Condition::INDEX_ACTION])) {
							if ($arguments[$form->getUid()][$page->getUid()][$fieldMarker][Condition::INDEX_ACTION] === Condition::ACTION_HIDE_STRING) {
								return;
							}
						}
					}
				}
			}
		} else {
			$page = $field->getPages();
			$form = $page->getForms()->getUid();
			$page = $page->getUid();
			if (!empty($arguments[Condition::INDEX_TODO][$form][$page][$fieldMarker][Condition::INDEX_ACTION])) {
				if ($arguments[Condition::INDEX_TODO][$form][$page][$fieldMarker][Condition::INDEX_ACTION] === Condition::ACTION_HIDE_STRING) {
					return;
				}
			}
		}
		parent::isValidField($field, $value);
	}
}
