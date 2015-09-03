<?php
namespace In2code\PowermailCond\Domain\Validator;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Validator\InputValidator;
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
		if (!empty($arguments['todo'][$fieldMarker])) {
			if (!$arguments['todo'][$fieldMarker] === Condition::getActionNumberMap(Condition::ACTION_HIDE)) {
				// Mandatory Check
				if ($field->getMandatory()) {
					if (!$this->validateMandatory($value)) {
						$this->setErrorAndMessage($field, 'mandatory');
					}
				}
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
