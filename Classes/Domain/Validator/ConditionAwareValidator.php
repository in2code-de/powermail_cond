<?php

declare(strict_types=1);

namespace In2code\PowermailCond\Domain\Validator;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Page;
use In2code\Powermail\Domain\Validator\InputValidator;
use In2code\Powermail\Utility\ConfigurationUtility;
use In2code\PowermailCond\Domain\Model\Condition;
use Throwable;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class ConditionAwareValidator extends InputValidator
{

    public function __construct(array $options = [])
    {
        parent::__construct($options);
    }

    /**
     * Validate a single field
     *
     * @param mixed $value
     * @throws Throwable
     */
    protected function isValidFieldInMandatoryValidation(Field $field, $value): void
    {
        $arguments = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->getSessionData('tx_powermail_cond');
        $parentPage = $field->getPage();
        if ($parentPage === null) {
            return;
        }
        $form = $parentPage->getForm();
        $formUid = $form->getUid();
        $pageUid = $parentPage->getUid();
        $marker = $field->getMarker();

        if (ConfigurationUtility::isReplaceIrreWithElementBrowserActive()) {
            /** @var Page $page */
            foreach ($form->getPages() as $page) {
                /** @var Field $field */
                foreach ($page->getFields() as $field) {
                    if (!empty($arguments[$formUid][$pageUid][$marker][Condition::INDEX_ACTION])) {
                        if ($arguments[$formUid][$pageUid][$marker][Condition::INDEX_ACTION] ===
                            Condition::ACTION_HIDE_STRING) {
                            return;
                        }
                    }
                }
            }
        } else {
            if (!empty($arguments[Condition::INDEX_TODO][$formUid][$pageUid][$marker][Condition::INDEX_ACTION])) {
                if ($arguments[Condition::INDEX_TODO][$formUid][$pageUid][$marker][Condition::INDEX_ACTION] ===
                    Condition::ACTION_HIDE_STRING) {
                    return;
                }
            }
        }
        parent::isValidFieldInMandatoryValidation($field, $value);
    }
}
