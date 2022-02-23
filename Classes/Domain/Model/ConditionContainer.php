<?php

declare(strict_types=1);

namespace In2code\PowermailCond\Domain\Model;

use In2code\Powermail\Domain\Model\Form;
use Throwable;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class ConditionContainer extends AbstractEntity
{
    protected int $loopCount = 0;

    /**
     * @var ObjectStorage<Condition>|null
     */
    protected ?ObjectStorage $conditions = null;

    protected bool $somethingChanged = true;

    /**
     * @throws Throwable
     */
    public function applyConditions(Form $form, array $arguments): array
    {
        // run this loop if any condition changed something
        // but stop after 100 rounds to prevent infinite loops (built by editors)
        while ($this->somethingChanged && $this->loopCount < 100) {
            $this->somethingChanged = false;
            $this->loopCount++;

            // go through each condition
            /** @var Condition $condition */
            foreach ($this->conditions as $condition) {
                // if the rules match on the form
                if ($condition->applies($form)) {
                    // then apply the changes the condition would make
                    $newArguments = $condition->apply($form, $arguments);
                } else {
                    // else "revert" the changes (un-hides previously hidden fields and vice versa)
                    $newArguments = $condition->negate($form, $arguments);
                }

                // If there were changes in the arguments (e.g. a field is now hidden)
                if ($newArguments !== $arguments) {
                    $this->somethingChanged = true;
                }

                // set the arguments for the next iteration
                $arguments = $newArguments;
            }
        }

        // return the arguments with instructions for the JS in the frontend
        $arguments['loops'] = $this->loopCount;
        return $arguments;
    }
}
