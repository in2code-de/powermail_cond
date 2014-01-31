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
		parent::isValid($params);
		$this->isValid = FALSE;
		return $this->isValid;
	}
}