<?php
namespace In2code\PowermailCond\Domain\Comparator;

use In2code\PowermailCond\Domain\Model\Rule;

/**
 * Class Comparator
 */
class Comparator {

	/**
	 * @var callable[]
	 */
	protected $callbacks = array();

	/**
	 *
	 */
	public function __construct() {
		$this->callbacks = array(
			Rule::OPERATOR_IS_SET => array($this, 'operationIsSet'),
			Rule::OPERATOR_NOT_IS_SET => array($this, 'operationNotIsSet'),
			Rule::OPERATOR_CONTAINS_VALUE => array($this, 'operationContainsValue'),
			Rule::OPERATOR_NOT_CONTAINS_VALUE => array($this, 'operationNotContainsValue'),
			Rule::OPERATOR_IS => array($this, 'operationIs'),
			Rule::OPERATOR_NOT_IS => array($this, 'operationNotIs'),
			Rule::OPERATOR_GREATER_THAN => array($this, 'operationGreaterThan'),
			Rule::OPERATOR_LESS_THAN => array($this, 'operationLessThan'),
			Rule::OPERATOR_CONTAINS_VALUE_FROM_FIELD => array($this, 'operationContainsValueFromField'),
			Rule::OPERATOR_NOT_CONTAINS_VALUE_FROM_FIELD => array($this, 'operationNotContainsValueFromField'),
		);
	}

	/**
	 * @param int $operator
	 * @return callable
	 */
	public function getCallbackForOperator($operator) {
		return $this->callbacks[$operator];
	}

	/**
	 * @param string $left
	 * @return bool
	 */
	public function operationIsSet($left) {
		return !$this->operationNotIsSet($left);
	}

	/**
	 * @param string $left
	 * @return bool
	 */
	public function operationNotIsSet($left) {
		return empty($left);
	}

	/**
	 * @param string $left
	 * @param string $right
	 * @return bool
	 */
	public function operationContainsValue($left, $right) {
		return (strpos($left, $right) !== FALSE);
	}

	/**
	 * @param string $left
	 * @param string $right
	 * @return bool
	 */
	public function operationNotContainsValue($left, $right) {
		return !$this->operationContainsValue($left, $right);
	}

	/**
	 * @param string $left
	 * @param string $right
	 * @return bool
	 */
	public function operationIs($left, $right) {
		return ($left === $right);
	}

	/**
	 * @param string $left
	 * @param string $right
	 * @return bool
	 */
	public function operationNotIs($left, $right) {
		return ($left !== $right);
	}

	/**
	 * @param string|int $left
	 * @param string|int $right
	 * @return bool
	 */
	public function operationGreaterThan($left, $right) {
		return (int) $left > (int) $right;
	}

	/**
	 * @param string|int $left
	 * @param string|int $right
	 * @return bool
	 */
	public function operationLessThan($left, $right) {
		return (int) $left < (int) $right;
	}


	public function operationContainsValueFromField($left, $right) {
		throw new \Exception('NOT YET IMPLEMENTED', 1441276049);
	}

	public function operationNotContainsValueFromField($left, $right) {
		throw new \Exception('NOT YET IMPLEMENTED', 1441276053);
	}
}
