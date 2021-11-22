<?php
declare(strict_types = 1);
namespace In2code\PowermailCond\Utility;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Utility\ArrayUtility;
use In2code\PowermailCond\Exception\UnsupportedVariableTypeException;

/**
 * Class FieldValueUtility
 */
class FieldValueUtility
{
    /**
     * @param Field $field
     * @param string|array $value
     * @return void
     * @throws UnsupportedVariableTypeException
     */
    public static function setValue(Field $field, $value): void
    {
        if (is_string($value)) {
            $field->setText($value);
        } elseif (is_array($value)) {
            $field->setText(json_encode($value));
        } else {
            throw new UnsupportedVariableTypeException('A value for a field can only be array or string', 1588236757);
        }
    }

    /**
     * @param Field $field
     * @return string|array
     */
    public static function getValue(Field $field)
    {
        $value = $field->getText();
        if (ArrayUtility::isJsonArray($value)) {
            $value = json_decode($value, true);
        }
        return $value;
    }
}
