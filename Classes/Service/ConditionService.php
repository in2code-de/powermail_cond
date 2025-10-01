<?php

declare(strict_types=1);

namespace In2code\PowermailCond\Service;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Domain\Model\Page;
use In2code\Powermail\Domain\Repository\FormRepository;
use In2code\PowermailCond\Domain\Repository\ConditionContainerRepository;
use In2code\PowermailCond\Exception\MissingPowermailParameterException;
use In2code\PowermailCond\Exception\UnsupportedVariableTypeException;

class ConditionService
{
    protected FormRepository $formRepository;

    protected ConditionContainerRepository $conditionContainerRepository;

    public function injectFormRepository(FormRepository $formRepository): void
    {
        $this->formRepository = $formRepository;
    }

    public function injectConditionContainerRepository(ConditionContainerRepository $conditionContainerRepository): void
    {
        $this->conditionContainerRepository = $conditionContainerRepository;
    }

    public function getArguments(array $powermailArguments = []): array
    {
        if (empty($powermailArguments['mail']['form'])) {
            throw new MissingPowermailParameterException();
        }

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

        foreach ($powermailArguments['field'] ?? [] as $fieldName => $fieldValue) {
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
            $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->setKey('ses', 'tx_powermail_cond', $arguments);
            unset($arguments['backup'], $arguments['field']);
        }

        return $arguments;
    }
}
