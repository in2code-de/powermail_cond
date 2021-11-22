<?php
namespace In2code\PowermailCond\Domain\Validator;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Page;
use In2code\Powermail\Domain\Validator\InputValidator;
use In2code\Powermail\Utility\ConfigurationUtility;
use In2code\PowermailCond\Domain\Model\Condition;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Extbase\Object\Exception;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;

/**
 * Class ConditionAwareValidator
 */
class ConditionAwareValidator extends InputValidator
{

    /**
     * Validate a single field
     *
     * @param Field $field
     * @param mixed $value
     * @return void
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws Exception
     */
    protected function isValidFieldInMandatoryValidation(Field $field, $value): void
    {
        $arguments = $this->getArgumentsFromSession();
        $parentPage = $field->getPage();
        if ($parentPage === null) {
            return;
        }
        $form = $parentPage->getForm();
        if ($form === null) {
            return;
        }
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
     * @return array
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected function getArgumentsFromSession()
    {
        /** @var FrontendUserAuthentication $feUser */
        $feUser = $GLOBALS['TSFE']->fe_user;
        return $feUser->getSessionData('tx_powermail_cond');
    }
}
