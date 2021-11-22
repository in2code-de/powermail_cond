<?php
namespace In2code\PowermailCond\Domain\Model;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Domain\Model\Page;
use In2code\PowermailCond\Domain\Comparator\Comparison;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Object\Exception;

/**
 * Class Rule
 */
class Rule extends AbstractEntity
{
    const TABLE_NAME = 'tx_powermailcond_domain_model_rule';

    const OPERATOR_IS_SET = 0;
    const OPERATOR_NOT_IS_SET = 1;
    const OPERATOR_CONTAINS_VALUE = 2;
    const OPERATOR_NOT_CONTAINS_VALUE = 3;
    const OPERATOR_IS = 4;
    const OPERATOR_NOT_IS = 5;
    const OPERATOR_GREATER_THAN = 6;
    const OPERATOR_LESS_THAN = 7;
    const OPERATOR_CONTAINS_VALUE_FROM_FIELD = 8;
    const OPERATOR_NOT_CONTAINS_VALUE_FROM_FIELD = 9;

    /**
     * Internal title
     *
     * @var string
     */
    protected $title = '';

    /**
     * relation to start field
     *
     * @var \In2code\Powermail\Domain\Model\Field
     */
    protected $startField = '';

    /**
     * 0 is set
     * 1 is not set
     * 2 contains
     * 3 contains not
     * 4 is
     * 5 is not
     * 6 greater than
     * 7 less than
     * 8 contains value from field
     * 9 contains not value from field
     *
     * @var int
     */
    protected $ops = 0;

    /**
     * @var string
     */
    protected $condString = '';

    /**
     * @var string
     */
    protected $equalField = '';

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Rule
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return Field
     */
    public function getStartField()
    {
        return $this->startField;
    }

    /**
     * @param Field $startField
     * @return Rule
     */
    public function setStartField($startField)
    {
        $this->startField = $startField;
        return $this;
    }

    /**
     * @return int
     */
    public function getOps()
    {
        return $this->ops;
    }

    /**
     * @param int $ops
     * @return Rule
     */
    public function setOps($ops)
    {
        $this->ops = $ops;
        return $this;
    }

    /**
     * @return string
     */
    public function getCondString()
    {
        return $this->condString;
    }

    /**
     * @param string $condString
     * @return Rule
     */
    public function setCondString($condString)
    {
        $this->condString = $condString;
        return $this;
    }

    /**
     * @return string
     */
    public function getEqualField()
    {
        return $this->equalField;
    }

    /**
     * @param string $equalField
     * @return Rule
     */
    public function setEqualField($equalField)
    {
        $this->equalField = $equalField;
        return $this;
    }

    /**
     * @param Form $form
     * @return bool
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws Exception
     */
    public function applies(Form $form)
    {
        $equalField = null;
        /** @var Page $page */
        if (((int)$this->equalField) > 0) {
            foreach ($form->getPages() as $page) {
                /** @var Field $field */
                foreach ($page->getFields() as $field) {
                    if ($field->getUid() === (int)$this->equalField) {
                        $equalField = $field;
                        break;
                    }
                }
                if ($equalField !== null) {
                    break;
                }
            }
        }
        /** @var Page $page */
        foreach ($form->getPages() as $page) {
            /** @var Field $field */
            foreach ($page->getFields() as $field) {
                if ($field !== null && $this->startField !== '' && $field->getUid() === $this->startField->getUid()) {
                    $comparison = new Comparison($this->ops);
                    if ($comparison->evaluate($field, $this->condString, $equalField)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
