<?php
namespace In2code\PowermailCond\Utility;

use TYPO3\CMS\Extbase\Utility\ArrayUtility as ArrayUtilityExtbase;

/**
 * Class ArrayUtility
 */
class ArrayUtility extends ArrayUtilityExtbase
{

    /**
     * Get quoted list from array
     *
     * @param array $array
     * @return string
     */
    public static function getQuotedList(array $array)
    {
        $list = '';
        foreach ($array as $value) {
            $list .= '"' . $value . '",';
        }
        return trim($list, ',');
    }

    /**
     * Unset part of array by given keys
     *
     * @param array $array
     * @param array $keys
     * @return void
     */
    public static function unsetByKeys(array &$array, array $keys)
    {
        foreach ($keys as $key) {
            unset($array[$key]);
        }
    }
}
