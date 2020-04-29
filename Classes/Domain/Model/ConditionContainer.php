<?php
namespace In2code\PowermailCond\Domain\Model;

use In2code\Powermail\Domain\Model\Form;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Object\Exception;

/**
 * Class ConditionContainer
 */
class ConditionContainer extends AbstractEntity
{
    const TABLE_NAME = 'tx_powermailcond_domain_model_conditioncontainer';

    /**
     * @var int
     */
    protected $loopCount = 0;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\In2code\PowermailCond\Domain\Model\Condition>
     */
    protected $conditions = null;

    /**
     * @var bool
     */
    protected $somethingChanged = true;

    /**
     * @param Form $form
     * @param array $arguments
     * @return array
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws Exception
     */
    public function applyConditions(Form $form, array $arguments)
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
