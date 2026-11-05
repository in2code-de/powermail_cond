<?php

declare(strict_types=1);

namespace In2code\PowermailCond\Domain\Validator;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Page;
use In2code\Powermail\Domain\Validator\InputValidator;
use In2code\Powermail\Utility\ConfigurationUtility;
use In2code\PowermailCond\Domain\Model\Condition;
use Throwable;
use TYPO3\CMS\Core\Utility\ArrayUtility;

class ConditionAwareValidator extends InputValidator
{
    private const ACTION_OPTIN_CONFIRM = 'optinConfirm';

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
        // do not revalidate again - the form has already been validated on submit
        // without valid frontend session (click doi-link later) there will be validation errors
        if ($this->getActionName() === self::ACTION_OPTIN_CONFIRM) {
            return;
        }

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

    /**
     * Returns the action of the original request, not the one after forwarding.
     */
    protected function getActionName(): string
    {
        $pluginVariables = $GLOBALS['TYPO3_REQUEST']->getQueryParams()['tx_powermail_pi1'] ?? [];
        $postParams = $GLOBALS['TYPO3_REQUEST']->getParsedBody()['tx_powermail_pi1'] ?? [];

        ArrayUtility::mergeRecursiveWithOverrule($pluginVariables, $postParams);

        return (string)($pluginVariables['action'] ?? '');
    }
}
