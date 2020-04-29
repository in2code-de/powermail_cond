<?php
namespace In2code\PowermailCond\Utility;

/**
 * Class ConfigurationUtility
 */
class ConfigurationUtility
{
    /**
     * Get path to an icon for TCA configuration
     *
     * @param string $fileName
     * @return string
     */
    public static function getIconPath($fileName)
    {
        return 'EXT:powermail_cond/Resources/Public/Icons/' . $fileName;
    }
}
