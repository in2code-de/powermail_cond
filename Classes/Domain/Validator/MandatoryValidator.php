<?php

/**
 * Class Tx_PowermailCond_Domain_Validator_MandatoryValidator
 */
class Tx_PowermailCond_Domain_Validator_MandatoryValidator extends Tx_Powermail_Domain_Validator_MandatoryValidator {

	/**
	 * Validation of given Params
	 *
	 * @param $params
	 * @return bool
	 */
	public function isValid($params) {
		return TRUE;
		$gp = t3lib_div::_GP('tx_powermail_pi1');
		$formUid = $gp['form'];
		$form = $this->formsRepository->findByUid($formUid);
		if (!method_exists($form, 'getPages')) {
			return $this->isValid;
		}

		/* @var $divCond Tx_PowermailCond_Utility_Div */
		$divCond = t3lib_div::makeInstance('Tx_PowermailCond_Utility_Div');
		$sessionValues = $divCond->getAllSessionValuesFromForm($formUid);
		t3lib_utility_Debug::debug($sessionValues, 'in2code Debug: ' . __FILE__ . ' in Line: ' . __LINE__);

		foreach ($form->getPages() as $page) { // every page in current form
			foreach ($page->getFields() as $field) { // every field in current page

				// if not a mandatory field
				if (!$field->getMandatory()) {
					continue;
				}

				// set error
				if (is_array($params[$field->getUid()])) {
					$empty = 1;
					foreach ($params[$field->getUid()] as $value) {
						if (strlen($value)) {
							$empty = 0;
							break;
						}
					}
					if ($empty) {
						$this->addError('mandatory', $field->getUid());
						$this->isValid = false;
					}
				} else {
					if (!strlen($params[$field->getUid()])) {
						$this->addError('mandatory', $field->getUid());
						$this->isValid = false;
					}
				}
			}
		}

		return $this->isValid;
	}
}