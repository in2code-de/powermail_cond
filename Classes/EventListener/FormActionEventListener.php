<?php

declare(strict_types=1);

namespace In2code\PowermailCond\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use In2code\Powermail\Events\FormControllerFormActionEvent;
use In2code\Powermail\Domain\Model\Form;
use In2code\PowermailCond\Domain\Repository\ConditionContainerRepository;

#[AsEventListener(
    identifier: 'in2code-de/form-action',
)]
final readonly class FormActionEventListener {
    protected ConditionContainerRepository $conditionContainerRepository;

    public function injectConditionContainerRepository(ConditionContainerRepository $conditionContainerRepository): void
    {
        $this->conditionContainerRepository = $conditionContainerRepository;
    }

    public function __invoke(FormControllerFormActionEvent $event): void
    {
        $form = $event->getForm();

        $conditionContainer = $this->conditionContainerRepository->findOneByForm($form->getUid());
        if ($conditionContainer !== null) {
            $form->setCss( $form->getCss() . " withCondition");
        }
    }
}
