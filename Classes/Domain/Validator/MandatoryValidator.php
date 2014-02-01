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
		$gp = t3lib_div::_GP('tx_powermail_pi1');
		$formUid = $gp['form'];
		$form = $this->formsRepository->findByUid($formUid);
		if (!method_exists($form, 'getPages')) {
			return $this->isValid;
		}

		/* @var $divCond Tx_PowermailCond_Utility_Div */
		$divCond = t3lib_div::makeInstance('Tx_PowermailCond_Utility_Div');
		$sessionValues = $divCond->getAllSessionValuesFromForm($formUid, 'deRequiredFields');

		// every page in current form
		foreach ($form->getPages() as $page) {
			// every field in current page
			foreach ($page->getFields() as $field) {

				// if not a mandatory field
				if (!$field->getMandatory()) {
					continue;
				}

				// set error
				if (is_array($params[$field->getUid()])) {
					$empty = TRUE;
					foreach ($params[$field->getUid()] as $value) {
						if (strlen($value)) {
							$empty = FALSE;
							break;
						}
					}
					if ($empty) {
						$this->addError('mandatory', $field->getUid());
						$this->isValid = FALSE;
					}
				} else {
					// extend this line for powermail_cond
					if (!strlen($params[$field->getUid()]) && !isset($sessionValues['field_' . $field->getUid()])) {
						$this->addError('mandatory', $field->getUid());
						$this->isValid = FALSE;
					}
				}
			}
		}

		return $this->isValid;
	}
}