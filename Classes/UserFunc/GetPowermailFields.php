<?php
namespace In2code\PowermailCond\UserFunc;

use Doctrine\DBAL\DBALException;
use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Domain\Model\Page;
use In2code\Powermail\Utility\ConfigurationUtility;
use In2code\Powermail\Utility\DatabaseUtility;
use In2code\PowermailCond\Domain\Model\Condition;
use In2code\PowermailCond\Domain\Model\ConditionContainer;
use In2code\PowermailCond\Utility\ArrayUtility;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class GetPowermailFields
 */
class GetPowermailFields
{

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var int
     */
    protected $formUid = 0;

    /**
     * @var array
     */
    protected $defaultFieldTypes = [
        'input',
        'textarea',
        'select',
        'radio',
        'check'
    ];

    /**
     * @param array $params
     * @return void
     * @throws DBALException
     */
    public function getFields(array &$params)
    {
        $this->initialize($params);
        $this->addFieldsToParams();
        $this->addFieldsetsToParams();
    }

    /**
     * @return void
     * @throws DBALException
     */
    protected function addFieldsToParams()
    {
        $fields = $this->getFieldsFromForm();
        $this->params['items'][] = [
            'powermail Fields',
            '--div--'
        ];
        foreach ($fields as $properties) {
            $this->params['items'][] = [
                $this->getLabelFromFieldProperties($properties),
                $properties['uid']
            ];
        }
    }

    /**
     * Add fieldsets to Params
     *
     * @return void
     */
    protected function addFieldsetsToParams()
    {
        if (!empty($this->params['config']['itemsProcFunc_addFieldsets'])) {
            $fieldsets = $this->getFieldsetsFromForm();
            $this->params['items'][] = [
                'powermail Fieldsets',
                '--div--'
            ];
            foreach ($fieldsets as $properties) {
                $this->params['items'][] = [
                    $properties['title'] . ' (' . $properties['uid'] . ')',
                    'fieldset:' . $properties['uid']
                ];
            }
        }
    }

    /**
     * @return array
     * @throws DBALException
     */
    protected function getFieldsFromForm()
    {
        $fieldsets = [];
        foreach ($this->getFieldsetsFromForm() as $row) {
            $fieldsets[] = $row['uid'];
        }

        if (count($fieldsets) == 0) {
            return [];
        }

        $queryBuilder = DatabaseUtility::getQueryBuilderForTable(Field::TABLE_NAME);
        $rows = (array)$queryBuilder
            ->select('uid', 'title', 'marker')
            ->from(Field::TABLE_NAME)
            ->where(
                $queryBuilder->expr()->in('type', explode(',', $this->getDefaultFieldTypesForQuery())),
                $queryBuilder->expr()->in('pages', $fieldsets)
            )
            ->orderBy('sorting')
            ->setMaxResults(10000)
            ->execute()
            ->fetchAll();

        $fields = [];
        foreach ($rows as $row) {
            $fields[] = $row;
        }
        return $fields;
    }

    /**
     * give me all fieldsets in an array
     *
     * @return array
     */
    protected function getFieldsetsFromForm()
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable(Page::TABLE_NAME);
        if (ConfigurationUtility::isReplaceIrreWithElementBrowserActive() === true) {
            $queryBuilderForms = DatabaseUtility::getQueryBuilderForTable(Form::TABLE_NAME);
            $formRow = (array)$queryBuilderForms
            ->select('pages')
            ->from(Form::TABLE_NAME)
            ->where('uid = ' . $this->getFormUid())
            ->execute()
            ->fetchAll();

            if (count($formRow) > 0 && !empty($formRow[0]['pages'])) {
                $whereClause = $queryBuilder->expr()->in('uid', explode(',', $formRow[0]['pages']));
            } else {
                return [];
            }
        } else {
            $whereClause = 'forms = ' . $this->getFormUid();
        }

        $queryBuilder = DatabaseUtility::getQueryBuilderForTable(Page::TABLE_NAME);
        $rows =  (array)$queryBuilder
            ->select('uid', 'title')
            ->from(Page::TABLE_NAME)
            ->addOrderBy('sorting')
            ->where($whereClause)
            ->execute()
            ->fetchAll();

        $fieldsets = [];
        foreach ($rows as $row) {
            $fieldsets[] = $row;
        }
        return $fieldsets;
    }

    /**
     * @param int $conditionUid
     * @return int
     * @throws DBALException
     */
    protected function getFormUidFromCondition(int $conditionUid): int
    {
        $query = 'select cc.form';
        $query .= ' from ' . ConditionContainer::TABLE_NAME . ' cc ' .
            'left join ' . Condition::TABLE_NAME . ' c on cc.uid = c.conditioncontainer';
        $query .= ' where c.uid = ' . (int)$conditionUid . ' AND c.hidden = 0 AND c.deleted = 0 limit 1';
        $connection = DatabaseUtility::getConnectionForTable(ConditionContainer::TABLE_NAME);
        return (int)$connection->executeQuery($query)->fetchColumn(0);
    }

    /**
     * Get Form Uid from Condition Container
     *
     * @param int $conditionContainerUid
     * @return int formUid
     */
    protected function getFormUidFromConditionContainer(int $conditionContainerUid): int
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable(ConditionContainer::TABLE_NAME);
        $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);
        return (int)$queryBuilder
            ->select('form')
            ->from(ConditionContainer::TABLE_NAME)
            ->where('uid=' . (int)$conditionContainerUid)
            ->addOrderBy('sorting')
            ->setMaxResults(1)
            ->execute()
            ->fetchColumn(0);
    }

    /**
     * Build a label
     *
     * @param array $properties
     * @return string
     */
    protected function getLabelFromFieldProperties(array $properties)
    {
        return $properties['title'] . ', {' . $properties['marker'] . '}, uid' . $properties['uid'];
    }

    /**
     * @param array $params
     * @return void
     * @throws DBALException
     */
    protected function initialize(array &$params)
    {
        $this->params = &$params;
        $this->setFormUid()->setDefaultFieldTypes();
    }

    /**
     * @return $this
     * @throws DBALException
     */
    public function setFormUid()
    {
        $formUid = (int)$this->params['row']['form'];
        if ($formUid === 0) {
            $formUid = $this->getFormUidFromConditionContainer((int)$this->params['row']['conditioncontainer']);
        }
        if (!empty($this->params['row']['conditions'])) {
            $formUid = $this->getFormUidFromCondition((int)$this->params['row']['conditions']);
        }

        if ($formUid === 0) {
            $formUid = $this->getFormUidFromAjaxRequest();
        }

        $this->formUid = $formUid;
        return $this;
    }

    /**
     * @return int
     */
    public function getFormUid()
    {
        return $this->formUid;
    }

    /**
     * @return GetPowermailFields
     */
    public function setDefaultFieldTypes()
    {
        if (!empty($this->params['config']['itemsProcFuncValue'])) {
            $this->defaultFieldTypes =
                GeneralUtility::trimExplode(',', $this->params['config']['itemsProcFuncValue'], true);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getDefaultFieldTypes()
    {
        return $this->defaultFieldTypes;
    }

    /**
     * @return string
     */
    public function getDefaultFieldTypesForQuery()
    {
        $fieldTypes = $this->getDefaultFieldTypes();
        return ArrayUtility::getQuotedList($fieldTypes);
    }

    protected function getFormUidFromAjaxRequest() {
        if (isset(GeneralUtility::_GP('ajax')[0])) {
            preg_match('/data-[0-9]*-' . ConditionContainer::TABLE_NAME . '-([0-9]*)/', GeneralUtility::_GP('ajax')[0], $matches);
            $conditionContainer = (int)$matches[1];
            return $this->getFormUidFromConditionContainer($conditionContainer);
        }
    }
}
