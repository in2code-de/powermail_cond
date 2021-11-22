<?php
namespace In2code\PowermailCond\UserFunc;

use Doctrine\DBAL\DBALException;
use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Domain\Model\Page;
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
        $query = 'select f.uid, f.title, f.marker';
        $query .= ' from ' . Field::TABLE_NAME . ' f ' .
            'left join ' . Page::TABLE_NAME . ' p on f.page = p.uid ' .
            'left join ' . Form::TABLE_NAME . ' fo on p.form = fo.uid';
        $query .= ' where f.hidden = 0 and f.deleted = 0 and f.type in (' . $this->getDefaultFieldTypesForQuery() . ')';
        if ($this->getFormUid() > 0) {
            $query .= ' and fo.uid = ' . $this->getFormUid();
        }
        $query .= ' order by f.sorting limit 10000';
        $connection = DatabaseUtility::getConnectionForTable(Field::TABLE_NAME);
        $rows = (array)$connection->executeQuery($query)->fetchAll();
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
        $rows = (array)$queryBuilder
            ->select('uid', 'title')
            ->from(Page::TABLE_NAME)
            ->where('form = ' . $this->getFormUid())
            ->addOrderBy('sorting')
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
        return (int)$connection->executeQuery($query)->fetchColumn();
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
            ->fetchColumn();
    }

    /**
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
}
