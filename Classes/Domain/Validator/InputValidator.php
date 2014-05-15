<?php
namespace In2code\PowermailCond\Domain\Validator;

use \TYPO3\CMS\Core\Utility\GeneralUtility,
	\In2code\Powermail\Domain\Model\Field;

/**
 * Class InputValidator
 */
class InputValidator extends \In2code\Powermail\Domain\Validator\InputValidator {

	/**
	 * Form UID
	 *
	 * @var int
	 */
	protected $formUid = 0;

	/**
	 * Validation of given Params
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return bool
	 */
	public function isValid($mail) {
		$this->formUid = $mail->getForm()->getUid();
		return parent::isValid($mail);
	}

	/**
	 * Validate a single field
	 *
	 * @param \In2code\Powermail\Domain\Model\Field $field
	 * @param \mixed $value
	 * @param $field
	 * @return void
	 */
	protected function isValidField(Field $field, $value) {
		/* @var $div \In2code\PowermailCond\Utility\Div */
		$div = GeneralUtility::makeInstance('\In2code\PowermailCond\Utility\Div');
		$sessionValues = $div->getAllSessionValuesFromForm($this->formUid, 'deRequiredFields');
		// stop process if this field is disabled
		if (array_key_exists('field_' . $field->getUid(), $sessionValues)) {
			return;
		}

		parent::isValidField($field, $value);
	}
}