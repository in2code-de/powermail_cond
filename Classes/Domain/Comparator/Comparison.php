<?php
namespace In2code\PowermailCond\Domain\Comparator;

use In2code\Powermail\Domain\Model\Field;
use In2code\PowermailCond\Domain\Model\Rule;
use In2code\PowermailCond\Utility\FieldValueUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Comparison
 */
class Comparison
{

    /**
     * @var int
     */
    protected $operation = 0;

    /**
     * @param int $operation
     */
    public function __construct($operation)
    {
        $this->operation = $operation;
    }

    /**
     * @param Field $leftField
     * @param string $valueToMatch
     * @param Field $rightField
     * @return bool
     */
    public function evaluate(Field $leftField, $valueToMatch = '', Field $rightField = null)
    {
        $result = false;
        switch ($this->operation) {
            case Rule::OPERATOR_IS_SET:
                $result = $this->operationIsNotEmpty(FieldValueUtility::getValue($leftField));
                break;
            case Rule::OPERATOR_NOT_IS_SET:
                $result = !$this->operationIsNotEmpty(FieldValueUtility::getValue($leftField));
                break;
            case Rule::OPERATOR_CONTAINS_VALUE:
                $result = $this->operationContains(FieldValueUtility::getValue($leftField), $valueToMatch);
                break;
            case Rule::OPERATOR_NOT_CONTAINS_VALUE:
                $result = !$this->operationContains(FieldValueUtility::getValue($leftField), $valueToMatch);
                break;
            case Rule::OPERATOR_IS:
                $result = (FieldValueUtility::getValue($leftField) === $valueToMatch);
                break;
            case Rule::OPERATOR_NOT_IS:
                $result = (FieldValueUtility::getValue($leftField) !== $valueToMatch);
                break;
            case Rule::OPERATOR_GREATER_THAN:
                if ($valueToMatch !== '') {
                    $result = (((int)FieldValueUtility::getValue($leftField)) > ((int)$valueToMatch));
                }
                break;
            case Rule::OPERATOR_LESS_THAN:
                if ($valueToMatch !== '') {
                    $result = (((int)FieldValueUtility::getValue($leftField)) < ((int)$valueToMatch));
                }
                break;
            case Rule::OPERATOR_CONTAINS_VALUE_FROM_FIELD:
                if ($rightField instanceof Field) {
                    $result = $this->operationContains(
                        FieldValueUtility::getValue($rightField),
                        FieldValueUtility::getValue($leftField)
                    );
                }
                break;
            case Rule::OPERATOR_NOT_CONTAINS_VALUE_FROM_FIELD:
                if ($rightField instanceof Field) {
                    $result = !$this->operationContains(
                        FieldValueUtility::getValue($rightField),
                        FieldValueUtility::getValue($leftField)
                    );
                }
                break;
        }
        return $result;
    }

    /**
     * @param $value
     * @return bool
     */
    protected function operationIsNotEmpty($value)
    {
        return !empty($value);
    }

    /**
     * @param string|array $haystack
     * @param string|array $needle If array, all elements must be contained in $haystack (OR)
     * @return bool
     */
    protected function operationContains($haystack, $needle)
    {
        if (!$this->operationIsNotEmpty($needle) || !$this->operationIsNotEmpty($haystack)) {
            return false;
        }
        if (strpos($needle, PHP_EOL)) {
            $needle = GeneralUtility::trimExplode(PHP_EOL, $needle);
        }
        if (is_array($needle)) {
            foreach ($needle as $needleString) {
                if ($this->operationIsNotEmpty($needleString)) {
                    if (strpos($haystack, $needleString) !== false) {
                        return true;
                    }
                }
            }
            return false;
        }
        if (is_array($haystack)) {
            return in_array($needle, $haystack);
        }
        return strpos($haystack, $needle) !== false;
    }
}
