<?php

declare(strict_types=1);

namespace In2code\PowermailCond\Controller;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Domain\Model\Page;
use In2code\Powermail\Domain\Repository\FormRepository;
use In2code\PowermailCond\Domain\Repository\ConditionContainerRepository;
use In2code\PowermailCond\Exception\MissingPowermailParameterException;
use In2code\PowermailCond\Exception\UnsupportedVariableTypeException;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

use function array_key_exists;
use function is_array;
use function is_string;
use function json_encode;

use const JSON_THROW_ON_ERROR;

class ConditionController extends ActionController
{
    protected FormRepository $formRepository;

    protected ConditionContainerRepository $conditionContainerRepository;

    protected TypoScriptFrontendController $typoscriptFrontendController;

    public function __construct()
    {
        $this->typoscriptFrontendController = $GLOBALS['TSFE'];
    }

    public function injectFormRepository(FormRepository $formRepository): void
    {
        $this->formRepository = $formRepository;
    }

    public function injectConditionContainerRepository(ConditionContainerRepository $conditionContainerRepository): void
    {
        $this->conditionContainerRepository = $conditionContainerRepository;
    }

    /**
     * Build Condition for AJAX call
     *
     * @throws Throwable
     */
    public function buildConditionAction(): ResponseInterface
    {
        $requestBody = $this->request->getParsedBody();
        if (empty($requestBody['tx_powermail_pi1']['mail']['form'])) {
            throw new MissingPowermailParameterException();
        }
        $powermailArguments = $requestBody['tx_powermail_pi1'];
        unset($powermailArguments['__referrer'], $powermailArguments['__trustedProperties']);

        /** @var Form $form */
        $form = $this->formRepository->findByIdentifier($powermailArguments['mail']['form']);

        /** @var array<string, Field> $fields */
        $fields = [];

        /** @var Page $page */
        foreach ($form->getPages() as $page) {
            /** @var Field $field */
            foreach ($page->getFields() as $field) {
                $fields[$field->getMarker()] = $field;
            }
        }

        foreach ($powermailArguments['field'] as $fieldName => $fieldValue) {
            if (!array_key_exists($fieldName, $fields)) {
                continue;
            }
            if (is_array($fieldValue)) {
                $fieldValue = json_encode($fieldValue, JSON_THROW_ON_ERROR);
            }
            if (!is_string($fieldValue)) {
                throw new UnsupportedVariableTypeException();
            }
            $fields[$fieldName]->setText($fieldValue);
        }

        $arguments = [];
        // Use the forms non-localized UID, because the field is l10n_mode exclude
        $conditionContainer = $this->conditionContainerRepository->findOneByForm($form->getUid());
        if ($conditionContainer !== null) {
            $arguments = $conditionContainer->applyConditions($form, $powermailArguments);
            $this->typoscriptFrontendController->fe_user->setAndSaveSessionData('tx_powermail_cond', $arguments);
            unset($arguments['backup'], $arguments['field']);
        }

        return $this->jsonResponse(json_encode($arguments, JSON_THROW_ON_ERROR));
    }
}
