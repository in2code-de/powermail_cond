<?php
namespace In2code\PowermailCond\Controller;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Domain\Model\Page;
use In2code\Powermail\Domain\Repository\FormRepository;
use In2code\PowermailCond\Domain\Repository\ConditionContainerRepository;
use In2code\PowermailCond\Utility\ArrayUtility;
use In2code\PowermailCond\Utility\SessionUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception\UnsupportedMethodException;

/**
 * Class ConditionController
 */
class ConditionController extends ActionController
{

    /**
     * @var array
     */
    protected $powermailArguments;

    /**
     * @return void
     * @throws \Exception
     */
    public function initializeBuildConditionAction()
    {
        $powermailArguments = (array)GeneralUtility::_GP('tx_powermail_pi1');
        if (!empty($powermailArguments)) {
            ArrayUtility::unsetByKeys($powermailArguments, ['__referrer', '__trustedProperties']);
            $this->powermailArguments = $powermailArguments;
        } else {
            throw new \Exception('No powermail parameters are given - e.g. &tx_powermail_pi1[mail][form]=123');
        }
    }

    /**
     * Build Condition for AJAX call
     *
     * @return string
     * @throws UnsupportedMethodException
     */
    public function buildConditionAction(): string
    {
        $arguments = [];
        $formRepository = $this->objectManager->get(FormRepository::class);
        /** @var Form $form */
        $form = $formRepository->findByIdentifier($this->powermailArguments['mail']['form']);
        $this->setTextFields($form);

        $containerRepository = $this->objectManager->get(ConditionContainerRepository::class);
        $conditionContainer = $containerRepository->findOneByForm($form->getUid());
        if ($conditionContainer !== null) {
            $arguments = $conditionContainer->applyConditions($form, $this->powermailArguments);
            SessionUtility::setSession($arguments);
            ArrayUtility::unsetByKeys($arguments, ['backup', 'field']);
        }
        return json_encode($arguments);
    }

    /**
     * @param Form $form
     * @return void
     */
    protected function setTextFields(Form $form)
    {
        if ($form !== null) {
            /** @var Page $page */
            foreach ($form->getPages() as $page) {
                /** @var Field $field */
                foreach ($page->getFields() as $field) {
                    foreach ($this->powermailArguments['field'] as $fieldName => $fieldValue) {
                        if ($field->getMarker() === $fieldName) {
                            $field->setText($fieldValue);
                        }
                    }
                }
            }
        }
    }
}
