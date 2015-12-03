<?php
namespace In2code\PowermailCond\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 in2code.de
 *  Alex Kellner <alexander.kellner@in2code.de>,
 *  Oliver Eglseder <oliver.eglseder@in2code.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use In2code\Powermail\Domain\Model\Form;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Condition Model
 *
 * @package powermail_cond
 * @license http://www.gnu.org/licenses/lgpl.html
 *            GNU Lesser General Public License, version 3 or later
 */
class ConditionContainer extends AbstractEntity
{

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
