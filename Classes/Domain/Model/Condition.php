<?php
namespace In2code\PowermailCond\Domain\Model;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Domain\Model\Page;
use In2code\Powermail\Domain\Repository\FieldRepository;
use In2code\Powermail\Domain\Repository\PageRepository;
use In2code\Powermail\Utility\ObjectUtility;
use In2code\PowermailCond\Exception\UnsupportedVariableTypeException;
use In2code\PowermailCond\Utility\FieldValueUtility;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Object\Exception;

/**
 * Class Condition
 */
class Condition extends AbstractEntity
{
    const TABLE_NAME = 'tx_powermailcond_domain_model_condition';

    const CONJUNCTION_OR = 'OR';
    const CONJUNCTION_AND = 'AND';
    const ACTION_HIDE = 0;
    const ACTION_UN_HIDE = 1;
    const ACTION_HIDE_STRING = 'hide';
    const ACTION_UN_HIDE_STRING = 'un_hide';
    const INDEX_TODO = 'todo';
    const INDEX_ACTION = '#action';
    const INDEX_BACKUP = 'backup';
    const INDEX_MATCHING_CONDITION = 'matching_condition';

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\In2code\PowermailCond\Domain\Model\Rule>
     */
    protected $rules = null;

    /**
     * @var string
     */
    protected $targetField = '';

    /**
     * 0 hide
     * 1 unhide
     *
     * @var int
     */
    protected $actions = 0;

    /**
     * @var string
     */
    protected $filterSelectField = '';

    /**
     * "OR"
     * "AND"
     *
     * @var string
     */
    protected $conjunction = '';

    /**
     * @var \In2code\Powermail\Domain\Model\Form
     */
    protected $form = '';

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Condition
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $rules
     * @return Condition
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * @return Field|Page|null
     * @throws Exception
     */
    public function getTargetField()
    {
        $targetField = $this->targetField;
        if (is_numeric($targetField)) {
            $fieldRepository = ObjectUtility::getObjectManager()->get(FieldRepository::class);
            /** @var Field $field */
            $field = $fieldRepository->findByUid((int)$targetField);
            return $field;
        }
        if (stristr($targetField, 'fieldset:')) {
            $pageRepository = ObjectUtility::getObjectManager()->get(PageRepository::class);
            /** @var Page $page */
            $page = $pageRepository->findByUid((int)trim($targetField, 'fieldset:'));
            return $page;
        }
        return null;
    }

    /**
     * @param string $targetField
     * @return Condition
     */
    public function setTargetField($targetField)
    {
        $this->targetField = $targetField;
        return $this;
    }

    /**
     * @return int
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param int $actions
     * @return Condition
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilterSelectField()
    {
        return $this->filterSelectField;
    }

    /**
     * @param string $filterSelectField
     * @return Condition
     */
    public function setFilterSelectField($filterSelectField)
    {
        $this->filterSelectField = $filterSelectField;
        return $this;
    }

    /**
     * @return string
     */
    public function getConjunction()
    {
        return $this->conjunction;
    }

    /**
     * @param string $conjunction
     * @return Condition
     */
    public function setConjunction($conjunction)
    {
        $this->conjunction = $conjunction;
        return $this;
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param Form $form
     * @return Condition
     */
    public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * @param Form $form
     * @return bool
     * @throws Exception
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public function applies(Form $form)
    {
        // If conjunction is or set $isOr to TRUE
        $isOr = ($this->conjunction === self::CONJUNCTION_OR);

        /** @var Rule $rule */
        foreach ($this->rules as $rule) {
            if ($rule->applies($form)) {
                if ($isOr === true) {
                    // if it is the first matching rule in an OR conjunction return TRUE
                    return true;
                }
            } elseif ($isOr === false) {
                // if it is the first NOT matching rule in an AND conjunction return FALSE
                return false;
            }
        }
        // if OR and no field matched: return TRUE
        // if AND and no field matched NOT: return FALSE
        return $isOr !== true;
    }

    /**
     * @param Form $form
     * @param array $arguments
     * @return array
     * @throws Exception
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws UnsupportedVariableTypeException
     */
    public function apply(Form $form, array $arguments)
    {
        if ($this->actions === self::ACTION_HIDE) {
            $action = self::ACTION_HIDE_STRING;
        } elseif ($this->actions === self::ACTION_UN_HIDE) {
            $action = self::ACTION_UN_HIDE_STRING;
        } else {
            return $arguments;
        }
        return $this->process($form, $arguments, $action);
    }

    /**
     * @param Form $form
     * @param array $arguments
     * @return array
     * @throws Exception
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws UnsupportedVariableTypeException
     */
    public function negate(Form $form, array $arguments)
    {
        if ($this->actions === self::ACTION_HIDE) {
            $action = self::ACTION_UN_HIDE_STRING;
        } elseif ($this->actions === self::ACTION_UN_HIDE) {
            $action = self::ACTION_HIDE_STRING;
        } else {
            return $arguments;
        }
        return $this->process($form, $arguments, $action);
    }

    /**
     * @param Form $form
     * @param array $arguments
     * @param string $action
     * @return array
     * @throws Exception
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws UnsupportedVariableTypeException
     */
    protected function process(Form $form, array $arguments, $action)
    {
        if (strpos($this->targetField, 'fieldset') !== false) {
            $targetPageUid = (int)substr($this->targetField, 9);
        } else {
            $this->targetField = (int)$this->targetField;
            $targetPageUid = false;
        }

        $formUid = $form->getUid();
        /** @var Page $page */
        foreach ($form->getPages() as $page) {
            $pageUid = $page->getUid();
            if ($targetPageUid && $pageUid === $targetPageUid) {
                return $this->applyOnPage($formUid, $page, $arguments, $action);
            }
            /** @var Field $field */
            foreach ($page->getFields() as $field) {
                if ($field->getUid() === $this->targetField) {
                    return $this->applyOnField($formUid, $pageUid, $field, $arguments, $action);
                }
            }
        }
        return $arguments;
    }

    /**
     * Show/Hide the Field if the Page is not hidden
     *
     * @param int $formUid
     * @param int $pageUid
     * @param Field $field
     * @param array $arguments
     * @param string $action
     * @param bool $weakRule A weak rule can not overrule a strong rule
     *        (e.g. a page get's shown [=weak] but another rule hides the field [=strong])
     * @return array
     * @throws Exception
     * @throws UnsupportedVariableTypeException
     */
    protected function applyOnField($formUid, $pageUid, Field $field, array $arguments, $action, $weakRule = false)
    {
        // IF there's an action set for the containing page
        if (!empty($arguments[self::INDEX_TODO][$formUid][$pageUid][self::INDEX_ACTION])) {
            $pageAction = $arguments[self::INDEX_TODO][$formUid][$pageUid][self::INDEX_ACTION];
            if ($pageAction === self::ACTION_HIDE_STRING) {
                // IF the condition tries to show a field on a hidden page prevent it
                if ($action === self::ACTION_UN_HIDE) {
                    return $arguments;
                }
            }
        }
        $fieldMarker = $field->getMarker();
        $conditionUid = $this->getUid();
        if (!empty($arguments[self::INDEX_TODO][$formUid][$pageUid][$fieldMarker][self::INDEX_ACTION])) {
            if ($weakRule
                && $arguments[self::INDEX_TODO][$formUid][$pageUid][$fieldMarker][self::INDEX_ACTION] !== $action
            ) {
                return $arguments;
            }
        }
        $arguments[self::INDEX_TODO][$formUid][$pageUid][$fieldMarker][self::INDEX_ACTION] = $action;
        $arguments[self::INDEX_TODO][$formUid][$pageUid][$fieldMarker][self::INDEX_MATCHING_CONDITION][$conditionUid] =
            $conditionUid;

        // Backup field value if field gets hidden
        if ($action === self::ACTION_HIDE_STRING) {
            $arguments[self::INDEX_BACKUP][$formUid][$pageUid][$fieldMarker] = FieldValueUtility::getValue($field);
            FieldValueUtility::setValue($field, '');
        } else {
            // fill field with backup'd value if field gets enabled again
            if ($action === self::ACTION_UN_HIDE_STRING) {
                if (isset($arguments[self::INDEX_BACKUP][$formUid][$pageUid][$fieldMarker])) {
                    FieldValueUtility::setValue(
                        $field,
                        $arguments[self::INDEX_BACKUP][$formUid][$pageUid][$fieldMarker]
                    );
                }
            }
        }
        return $arguments;
    }

    /**
     * @param int $formUid
     * @param Page $page
     * @param array $arguments
     * @param string $action
     * @return array
     * @throws Exception
     * @throws UnsupportedVariableTypeException
     */
    protected function applyOnPage($formUid, Page $page, array $arguments, $action)
    {
        $pageUid = $page->getUid();
        foreach ($page->getFields() as $field) {
            $arguments = $this->applyOnField(
                $formUid,
                $pageUid,
                $field,
                $arguments,
                $action,
                ($action === self::ACTION_UN_HIDE_STRING)
            );
        }
        $conditionUid = $this->getUid();
        $arguments[self::INDEX_TODO][$formUid][$pageUid][self::INDEX_ACTION] = $action;
        $arguments[self::INDEX_TODO][$formUid][$pageUid][self::INDEX_MATCHING_CONDITION][$conditionUid] = $conditionUid;
        return $arguments;
    }
}
