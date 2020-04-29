<?php
namespace In2code\PowermailCond\Utility;

/**
 * Class ArrayUtility
 */
class ArrayUtility
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
