<?php
namespace In2code\PowermailCond\Utility;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class SessionUtility
 */
class SessionUtility
{
    /**
     * Get quoted list from array
     *
     * @param array $array
     * @return void
     */
    public static function setSession(array $array)
    {
        $typoScriptFrontend = self::getTyposcriptFrontendController();
        $typoScriptFrontend->fe_user->setAndSaveSessionData('tx_powermail_cond', $array);
    }

    /**
     * @return TypoScriptFrontendController
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected static function getTyposcriptFrontendController()
    {
        return $GLOBALS['TSFE'];
    }
}
