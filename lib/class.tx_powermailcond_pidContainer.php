<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Alexander Kellner <alexander.kellner@in2code.de>, in2code.
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
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
 * This class adds a pid container hidden field at the end of a powermail form
 *
 * @author	Alex Kellner <alexander.kellner@in2code.de>, in2code.
 * @package	TYPO3
 * @subpackage	tx_powermailcond_pidContainer
 */
class tx_powermailcond_pidContainer {

	public $extKey = 'powermail_cond'; // Extension key
	public $prefixId = 'tx_powermailcond_pi1';
	
	/**
	 * Return all values from the session (could be used for debugging, etc..)
	 *
	 * @param	array	Outer Marker Array
	 * @param	array	Subpart Array
	 * @param	array	TypoScript configuration
	 * @param	object	Parent object
	 * @return	void
	 */
	public function PM_FormWrapMarkerHook($markerArray, &$subpartArray, $conf, $pObj) {
		if (isset($subpartArray['###POWERMAIL_CONTENT###'])) {
			$subpartArray['###POWERMAIL_CONTENT###'] .= '<input type="hidden" id="powermail_cond_pid_container" name="powermail_cond_pid_container" value="' . intval($GLOBALS['TSFE']->id) . '" />';
		}
	}
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/powermail_cond/lib/class.tx_powermailcond_div.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/powermail_cond/lib/class.tx_powermailcond_div.php']);
}
?>