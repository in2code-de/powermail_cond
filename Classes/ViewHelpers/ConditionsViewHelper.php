<?php

declare(strict_types=1);

namespace In2code\PowermailCond\ViewHelpers;

use In2code\Powermail\Domain\Model\Form;
use In2code\PowermailCond\Service\ConditionService;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class ConditionsViewHelper
 */
class ConditionsViewHelper extends AbstractViewHelper
{
    protected ConditionService $conditionService;

    public function injectConditionService(ConditionService $conditionService): void
    {
        $this->conditionService = $conditionService;
    }

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('form', Form::class, 'Form', true);
    }

    /**
     * Returns Data Attribute Array to enable validation
     *
     * @return string
     */
    public function render(): string
    {
        /** @var Form $field */
        $form = $this->arguments['form'];

        if ($this->renderingContext->getRequest()->getParsedBody()) {
            $params = $this->renderingContext->getRequest()->getParsedBody()['tx_powermail_pi1'];
        } else {
            $params = ['mail' => ['form' => $form->getUid()]];
        }

        $arguments = $this->conditionService->getArguments($params);

        return json_encode($arguments, JSON_THROW_ON_ERROR);
    }
}
