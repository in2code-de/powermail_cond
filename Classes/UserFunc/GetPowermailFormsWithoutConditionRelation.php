<?php
namespace In2code\PowermailCond\UserFunc;

use Doctrine\DBAL\DBALException;
use In2code\Powermail\Utility\DatabaseUtility;
use In2code\PowermailCond\Domain\Model\ConditionContainer;

/**
 * Get powermail forms that have no related condition containers
 *
 * Class GetPowermailFormsWithoutConditionRelation
 */
class GetPowermailFormsWithoutConditionRelation
{

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var int
     */
    protected $currentFormUid = 0;

    /**
     * remove forms that are already related to a condition container
     *
     * @param array $params
     * @return void
     * @throws DBALException
     */
    public function filterForms(array &$params)
    {
        $this->initialize($params);
        foreach ((array)$this->params['items'] as $key => $form) {
            if ($this->hasFormRelatedConditionContainers((int)$form[1]) && (int)$form[1] !== $this->currentFormUid) {
                unset($this->params['items'][$key]);
            }
        }
    }

    /**
     * @param int $formUid
     * @return bool
     * @throws DBALException
     */
    protected function hasFormRelatedConditionContainers(int $formUid): bool
    {
        $connection = DatabaseUtility::getConnectionForTable(ConditionContainer::TABLE_NAME);
        $query
            = 'select uid from ' . ConditionContainer::TABLE_NAME . ' where form=' . (int)$formUid . ' and deleted=0';
        return $connection->executeQuery($query)->fetchColumn() !== false;
    }

    /**
     * @param array $params
     * @return void
     */
    protected function initialize(array &$params)
    {
        $this->params = &$params;
        $this->currentFormUid = (int)$this->params['row']['form'];
    }
}
