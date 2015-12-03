<?php
namespace In2code\PowermailCond\Controller;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Domain\Model\Page;
use In2code\PowermailCond\Domain\Model\ConditionContainer;
use In2code\PowermailCond\Utility\ArrayUtility;
use In2code\PowermailCond\Utility\SessionUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

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

/**
 * ConditionController
 *
 * @package powermail_cond
 * @license http://www.gnu.org/licenses/lgpl.html
 *            GNU Lesser General Public License, version 3 or later
 */
class ConditionController extends ActionController
{

    /**
     * @var \In2code\Powermail\Domain\Repository\FormRepository
     * @inject
     */
    protected $formRepository;

    /**
     * @var \In2code\PowermailCond\Domain\Repository\ConditionContainerRepository
     * @inject
     */
    protected $containerRepository;

    /**
     * @var array
     */
    protected $powermailArguments;

    /**
     * @return void
     */
    public function initializeBuildConditionAction()
    {
        $powermailArguments = (array) GeneralUtility::_GP('tx_powermail_pi1');
        ArrayUtility::unsetByKeys($powermailArguments, ['__referrer', '__trustedProperties']);
        $this->powermailArguments = $powermailArguments;
    }

    /**
     * Build Condition for AJAX call
     *
     * @return string
     */
    public function buildConditionAction()
    {
        /** @var Form $form */
        $form = $this->formRepository->findByIdentifier($this->powermailArguments['mail']['form']);
        $this->setTextFields($form);

        /** @var ConditionContainer $conditionContainer */
        $conditionContainer = $this->containerRepository->findOneByForm($form);
        if ($conditionContainer !== null) {
            $arguments = $conditionContainer->applyConditions($form, $this->powermailArguments);
            SessionUtility::setSession($arguments);
            ArrayUtility::unsetByKeys($arguments, ['backup', 'field']);
            return json_encode($arguments);
        }
        return null;
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
