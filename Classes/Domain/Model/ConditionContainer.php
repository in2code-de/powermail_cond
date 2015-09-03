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
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Alex Kellner <alexander.kellner@in2code.de>, in2code.de
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
 * Condition Model
 *
 * @package powermail_cond
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class ConditionContainer {

	/**
	 * @var int
	 */
	protected $loopCount = 0;

	/**
	 * @var Condition[]
	 */
	protected $conditions = array();

	/**
	 * @var bool
	 */
	protected $somethingChanged = TRUE;

	/**
	 * @param QueryResult $conditions
	 */
	public function __construct(QueryResult $conditions) {
		$this->conditions = $conditions;
	}

	/**
	 * @param Form $form
	 * @param array $arguments
	 * @return Form
	 */
	public function applyConditions(Form $form, array $arguments) {
		while ($this->somethingChanged && $this->loopCount < 100) {
			$this->somethingChanged = FALSE;
			$this->loopCount++;
			foreach ($this->conditions as $condition) {
				if ($condition->applies($form)) {
					$newArguments = $condition->apply($form, $arguments);
				} else {
					$newArguments = $condition->negate($form, $arguments);
				}
				if ($newArguments !== $arguments) {
					$this->somethingChanged = TRUE;
				}
				$arguments = $newArguments;
			}
		}
		return $arguments;
	}

}
