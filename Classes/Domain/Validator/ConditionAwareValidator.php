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

		// Mandatory Check
		if ($field->getMandatory()) {
			if (!$this->validateMandatory($value)) {
				$this->setErrorAndMessage($field, 'mandatory');
			}
		}

		// String Checks
		if (!empty($value) && in_array($field->getType(), $this->validationFieldTypes)) {
			switch ($field->getValidation()) {

				// email
				case 1:
					if (!$this->validateEmail($value)) {
						$this->setErrorAndMessage($field, 'validation.' . $field->getValidation());
					}
					break;

				// URL
				case 2:
					if (!$this->validateUrl($value)) {
						$this->setErrorAndMessage($field, 'validation.' . $field->getValidation());
					}
					break;

				// phone
				case 3:
					if (!$this->validatePhone($value)) {
						$this->setErrorAndMessage($field, 'validation.' . $field->getValidation());
					}
					break;

				// numbers only
				case 4:
					if (!$this->validateNumbersOnly($value)) {
						$this->setErrorAndMessage($field, 'validation.' . $field->getValidation());
					}
					break;

				// letters only
				case 5:
					if (!$this->validateLettersOnly($value)) {
						$this->setErrorAndMessage($field, 'validation.' . $field->getValidation());
					}
					break;

				// min number
				case 6:
					if (!$this->validateMinNumber($value, $field->getValidationConfiguration())) {
						$this->setErrorAndMessage($field, 'validation.' . $field->getValidation());
					}
					break;

				// max number
				case 7:
					if (!$this->validateMaxNumber($value, $field->getValidationConfiguration())) {
						$this->setErrorAndMessage($field, 'validation.' . $field->getValidation());
					}
					break;

				// range
				case 8:
					if (!$this->validateRange($value, $field->getValidationConfiguration())) {
						$this->setErrorAndMessage($field, 'validation.' . $field->getValidation());
					}
					break;

				// length
				case 9:
					if (!$this->validateLength($value, $field->getValidationConfiguration())) {
						$this->setErrorAndMessage($field, 'validation.' . $field->getValidation());
					}
					break;

				// pattern
				case 10:
					if (!$this->validatePattern($value, $field->getValidationConfiguration())) {
						$this->setErrorAndMessage($field, 'validation.' . $field->getValidation());
					}
					break;

				/**
				 * E.g. Validation was extended with Page TSconfig
				 * 		tx_powermail.flexForm.validation.addFieldOptions.100 = New Validation
				 *
				 * Register your Class and Method with TypoScript Setup
				 * 		plugin.tx_powermail.settings.setup.validation.customValidation.100 =
				 * 			In2code\Powermailextended\Domain\Validator\ZipValidator
				 *
				 * Add method to your class
				 * 		validate100($value, $validationConfiguration)
				 *
				 * Define your Errormessage with TypoScript Setup
				 * 		plugin.tx_powermail._LOCAL_LANG.default.validationerror_validation.100 =
				 * 			Error happens!
				 */
				default:
					if ($field->getValidation()) {
						$validation = $field->getValidation();
						if (!empty($this->settings['validation.']['customValidation.'][$validation])) {
							$extendedValidator = $this->objectManager->get($this->settings['validation.']['customValidation.'][$validation]);
							if (method_exists($extendedValidator, 'validate' . ucfirst($validation))) {
								if (!$extendedValidator->{'validate' . ucfirst($validation)}($value, $field->getValidationConfiguration())) {
									$this->setErrorAndMessage($field, 'validation.' . $validation);
								}
							}
						}
					}
			}
		}
	}
}
