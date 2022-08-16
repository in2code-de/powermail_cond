<?php

declare(strict_types=1);

namespace In2code\PowermailCond\Domain\Model;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Domain\Model\Page;
use In2code\Powermail\Domain\Repository\FieldRepository;
use In2code\Powermail\Domain\Repository\PageRepository;
use Throwable;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

use function is_numeric;
use function stripos;
use function strpos;
use function substr;

class Condition extends AbstractEntity
{
    public const CONJUNCTION_OR = 'OR';
    public const CONJUNCTION_AND = 'AND';
    public const ACTION_HIDE = 0;
    public const ACTION_UN_HIDE = 1;
    public const ACTION_HIDE_STRING = 'hide';
    public const ACTION_UN_HIDE_STRING = 'un_hide';
    public const INDEX_TODO = 'todo';
    public const INDEX_ACTION = '#action';
    public const INDEX_BACKUP = 'backup';
    public const INDEX_MATCHING_CONDITION = 'matching_condition';

    /**
     * @var string
     */
    protected string $title = '';

    /**
     * @var ObjectStorage<Rule>
     */
    protected ?ObjectStorage $rules = null;

    /**
     * @var string
     */
    protected string $targetField = '';

    /**
     * @var int
     */
    protected int $actions = self::ACTION_HIDE;

    /**
     * @var string
     */
    protected string $filterSelectField = '';

    /**
     * @var string
     */
    protected string $conjunction = '';

    /**
     * @var Form|null
     */
    protected ?Form $form = null;

    public function __construct()
    {
        $this->rules = new ObjectStorage();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return ObjectStorage<Rule>
     */
    public function getRules(): ObjectStorage
    {
        return $this->rules;
    }

    /**
     * @param ObjectStorage<Rule> $rules
     */
    public function setRules(ObjectStorage $rules): void
    {
        $this->rules = $rules;
    }

    /**
     * @return Field|Page|null
     */
    public function getTargetField()
    {
        $targetField = $this->targetField;
        if (is_numeric($targetField)) {
            $fieldRepository = GeneralUtility::makeInstance(FieldRepository::class);
            /** @var Field $field */
            return $fieldRepository->findByUid((int)$targetField);
        }
        if (stripos($targetField, 'fieldset:') !== false) {
            $pageRepository = GeneralUtility::makeInstance(PageRepository::class);
            /** @var Page $page */
            $uid = substr($targetField, 9);
            return $pageRepository->findByUid((int)$uid);
        }
        return null;
    }

    public function setTargetField(string $targetField): void
    {
        $this->targetField = $targetField;
    }

    public function getActions(): int
    {
        return $this->actions;
    }

    public function setActions(int $actions): void
    {
        $this->actions = $actions;
    }

    public function getFilterSelectField(): string
    {
        return $this->filterSelectField;
    }

    public function setFilterSelectField(string $filterSelectField): void
    {
        $this->filterSelectField = $filterSelectField;
    }

    public function getConjunction(): string
    {
        return $this->conjunction;
    }

    public function setConjunction(string $conjunction): void
    {
        $this->conjunction = $conjunction;
    }

    public function getForm(): ?Form
    {
        return $this->form;
    }

    public function setForm(Form $form): void
    {
        $this->form = $form;
    }

    /**
     * @throws Throwable
     */
    public function apply(Form $form, array $arguments): array
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
     * @throws Throwable
     */
    public function negate(Form $form, array $arguments): array
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
     * @throws Throwable
     */
    protected function process(Form $form, array $arguments, string $action): array
    {
        if (strpos($this->targetField, 'fieldset') !== false) {
            return $this->processPage($form, $arguments, $action);
        }

        return $this->processField($form, $arguments, $action);
    }

    /**
     * @throws Throwable
     */
    protected function processPage(Form $form, array $arguments, string $action): array
    {
        $targetPageUid = (int)substr($this->targetField, 9);

        $formUid = $form->getUid();
        /** @var Page $page */
        foreach ($form->getPages() as $page) {
            if ($page->getUid() === $targetPageUid) {
                return $this->applyOnPage($formUid, $page, $arguments, $action);
            }
        }
        return $arguments;
    }

    /**
     * @throws Throwable
     */
    protected function processField(Form $form, array $arguments, string $action): array
    {
        $formUid = $form->getUid();
        $fieldUid = (int)$this->targetField;

        /** @var Page $page */
        foreach ($form->getPages() as $page) {
            /** @var Field $field */
            foreach ($page->getFields() as $field) {
                if ($field->getUid() === $fieldUid) {
                    return $this->applyOnField($formUid, $page->getUid(), $field, $arguments, $action);
                }
            }
        }
        return $arguments;
    }

    /**
     * Show/Hide the Field if the Page is not hidden
     *
     * @param bool $weakRule A weak rule can not overrule a strong rule (e.g. a page gets shown [=weak] but another rule
     *  hides the field [=strong])
     * @throws Throwable
     */
    protected function applyOnField(
        int $formUid,
        int $pageUid,
        Field $field,
        array $arguments,
        string $action,
        bool $weakRule = false
    ): array {
        $fieldMarker = $field->getMarker();
        $conditionUid = $this->getUid();
        if (
            !empty($arguments[self::INDEX_TODO][$formUid][$pageUid][$fieldMarker][self::INDEX_ACTION])
            && $weakRule
            && $arguments[self::INDEX_TODO][$formUid][$pageUid][$fieldMarker][self::INDEX_ACTION] !== $action
        ) {
            return $arguments;
        }
        $arguments[self::INDEX_TODO][$formUid][$pageUid][$fieldMarker][self::INDEX_ACTION] = $action;
        $arguments[self::INDEX_TODO][$formUid][$pageUid][$fieldMarker][self::INDEX_MATCHING_CONDITION][$conditionUid] =
            $conditionUid;

        // Backup field value if field gets hidden
        if ($action === self::ACTION_HIDE_STRING) {
            $arguments[self::INDEX_BACKUP][$formUid][$pageUid][$fieldMarker] = $field->getText();
            $field->setText('');
        }

        if (
            $action === self::ACTION_UN_HIDE_STRING
            && isset($arguments[self::INDEX_BACKUP][$formUid][$pageUid][$fieldMarker])
        ) {
            // fill field with backup'd value if field gets enabled again
            $field->setText($arguments[self::INDEX_BACKUP][$formUid][$pageUid][$fieldMarker]);
        }
        return $arguments;
    }

    /**
     * @throws Throwable
     */
    protected function applyOnPage(int $formUid, Page $page, array $arguments, string $action): array
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

    /**
     * @throws Throwable
     */
    public function applies(Form $form): bool
    {
        // If conjunction is or set $isOr to TRUE
        $isOr = $this->conjunction === self::CONJUNCTION_OR;

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
}
