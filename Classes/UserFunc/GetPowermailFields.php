<?php
namespace In2code\PowermailCond\UserFunc;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Domain\Model\Page;
use In2code\PowermailCond\Utility\ArrayUtility;
use TYPO3\CMS\Backend\Form\FormEngine;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class GetPowermailFields
 */
class GetPowermailFields
{

    /**
     * @var \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected $databaseConnection = null;

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
     * show all fields in the backend
     *
     * @param array $params
     * @return void
     */
    public function getFields(array &$params)
    {
        $this->initialize($params);
        $this->addFieldsToParams();
        $this->addFieldsetsToParams();
    }

    /**
     * Add fields to params array
     *
     * @return void
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
     * Get fields
     *
     * @return array
     */
    protected function getFieldsFromForm()
    {
        $fields = [];
        $select = 'f.uid, f.title, f.marker';
        $from = Field::TABLE_NAME . ' f ' .
            'left join ' . Page::TABLE_NAME . ' p on f.pages = p.uid ' .
            'left join ' . Form::TABLE_NAME . ' fo on p.forms = fo.uid';
        $where = 'f.hidden = 0 and f.deleted = 0 and f.type in (' . $this->getDefaultFieldTypesForQuery() . ')';
        if ($this->getFormUid() > 0) {
            $where .= ' and fo.uid = ' . $this->getFormUid();
        }
        $groupBy = '';
        $orderBy = 'f.sorting';
        $limit = 10000;
        $res = $this->databaseConnection->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        if ($res) {
            while (($row = $this->databaseConnection->sql_fetch_assoc($res))) {
                $fields[] = $row;
            }
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
        $fieldsets = [];
        $select = 'uid, title';
        $from = Page::TABLE_NAME;
        $where = 'forms = ' . $this->getFormUid() . ' AND hidden = 0 AND deleted = 0';
        $groupBy = '';
        $orderBy = 'sorting';
        $limit = '';
        $res = $this->databaseConnection->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        if ($res) {
            while (($row = $this->databaseConnection->sql_fetch_assoc($res))) {
                $fieldsets[] = $row;
            }
        }
        return $fieldsets;
    }

    /**
     * Get Form Uid from Rule
     *
     * @param int $conditionUid
     * @return int formUid
     */
    protected function getFormUidFromCondition($conditionUid)
    {
        $select = 'cc.form';
        $from = 'tx_powermailcond_domain_model_conditioncontainer cc ' .
            'left join tx_powermailcond_domain_model_condition c on cc.uid = c.conditioncontainer';
        $where = 'c.uid = ' . (int) $conditionUid . ' AND c.hidden = 0 AND c.deleted = 0';
        $groupBy = '';
        $orderBy = '';
        $limit = 1;
        $res = $this->databaseConnection->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        if ($res) {
            $row = $this->databaseConnection->sql_fetch_assoc($res);
            return (int) $row['form'];
        }
        return 0;
    }

    /**
     * Get Form Uid from Condition Container
     *
     * @param int $conditionContainerUid
     * @return int formUid
     */
    protected function getFormUidFromConditionContainer($conditionContainerUid)
    {
        $select = 'cc.form';
        $from = 'tx_powermailcond_domain_model_conditioncontainer cc';
        $where = 'cc.uid = ' . (int) $conditionContainerUid . ' AND cc.deleted = 0';
        $groupBy = '';
        $orderBy = '';
        $limit = 1;
        $res = $this->databaseConnection->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        if ($res) {
            $row = $this->databaseConnection->sql_fetch_assoc($res);
            return (int) $row['form'];
        }
        return 0;
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
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected function initialize(array &$params)
    {
        $this->databaseConnection = $GLOBALS['TYPO3_DB'];
        $this->params = &$params;
        $this->setFormUid()->setDefaultFieldTypes();
    }

    /**
     * @return GetPowermailFields
     */
    public function setFormUid()
    {
        $formUid = (int) $this->params['row']['form'];
        if ($formUid === 0) {
            $formUid = $this->getFormUidFromConditionContainer((int) $this->params['row']['conditioncontainer']);
        }
        if (!empty($this->params['row']['conditions'])) {
            $formUid = $this->getFormUidFromCondition($this->params['row']['conditions']);
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
