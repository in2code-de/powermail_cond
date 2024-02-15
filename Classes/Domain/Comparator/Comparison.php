<?php

declare(strict_types=1);

namespace In2code\PowermailCond\Domain\Comparator;

use In2code\Powermail\Domain\Model\Field;
use In2code\PowermailCond\Domain\Model\Rule;
use function in_array;
use function is_array;
use function json_decode;
use const JSON_THROW_ON_ERROR;
use JsonException;
use const PHP_EOL;
use function strpos;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Comparison
{
    protected int $operation = 0;

    public function __construct(int $operation)
    {
        $this->operation = $operation;
    }

    public function evaluate(Field $leftField, string $valueToMatch, ?Field $rightField): bool
    {
        $leftFieldValue = $this->getFieldValue($leftField);
        switch ($this->operation) {
            case Rule::OPERATOR_IS_SET:
                return !empty($leftFieldValue);
            case Rule::OPERATOR_NOT_IS_SET:
                return empty($leftFieldValue);
            case Rule::OPERATOR_CONTAINS_VALUE:
                return $this->operationContains($leftFieldValue, $valueToMatch);
            case Rule::OPERATOR_NOT_CONTAINS_VALUE:
                return !$this->operationContains($leftFieldValue, $valueToMatch);
            case Rule::OPERATOR_IS:
                return $leftFieldValue === $valueToMatch;
            case Rule::OPERATOR_NOT_IS:
                return $leftFieldValue !== $valueToMatch;
            case Rule::OPERATOR_GREATER_THAN:
                if ($valueToMatch !== '') {
                    return (int)$leftFieldValue > (int)$valueToMatch;
                }
                return false;
            case Rule::OPERATOR_LESS_THAN:
                if ($valueToMatch !== '') {
                    return (int)$leftFieldValue < (int)$valueToMatch;
                }
                return false;
            case Rule::OPERATOR_CONTAINS_VALUE_FROM_FIELD:
                if (!$rightField instanceof Field) {
                    return false;
                }
                $rightFieldValue = $this->getFieldValue($rightField);
                return $this->operationContains($rightFieldValue, $leftFieldValue);
            case Rule::OPERATOR_NOT_CONTAINS_VALUE_FROM_FIELD:
                if (!$rightField instanceof Field) {
                    return false;
                }
                $rightFieldValue = $this->getFieldValue($rightField);
                return !$this->operationContains($rightFieldValue, $leftFieldValue);
        }
        return false;
    }

    /**
     * @return string|array
     */
    public function getFieldValue(Field $field)
    {
        $value = $field->getText();
        if (($value[0] ?? '') === '{' || ($value[0] ?? '') === '[') {
            try {
                return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $exception) {
                // No JSON, no problem.
            }
        }
        return $value;
    }

    /**
     * @param string|array $haystack
     * @param string|array $needle If it is an array, all elements must be contained in $haystack (OR)
     */
    protected function operationContains($haystack, $needle): bool
    {
        if (empty($needle) || empty($haystack)) {
            return false;
        }
        if (strpos((string)$needle, PHP_EOL)) {
            $needle = GeneralUtility::trimExplode(PHP_EOL, $needle);
        }
        if (is_array($needle)) {
            foreach ($needle as $needleString) {
                if (!empty($needleString) && str_contains($haystack, $needleString)) {
                    return true;
                }
            }
            return false;
        }
        if (is_array($haystack)) {
            return in_array($needle, $haystack, false);
        }
        return str_contains($haystack, $needle);
    }
}
