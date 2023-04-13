<?php

declare(strict_types=1);

namespace In2code\PowermailCond\UserFunc;

use Throwable;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;

use function array_keys;
use function in_array;

/**
 * Remove all forms from the selectable items that are already selected in another condition container.
 */
class GetPowermailFormsWithoutConditionRelation
{
    protected ConnectionPool $connectionPool;

    public function __construct(ConnectionPool $connectionPool)
    {
        $this->connectionPool = $connectionPool;
    }

    /**
     * @throws Throwable
     */
    public function filterForms(array &$params): void
    {
        $currentForm = (int)$params['row']['form'];
        $formsToSkip = [0, $currentForm];

        $availableForms = [];
        $items = (array)$params['items'];
        foreach ($items as $key => $form) {
            $formUid = (int)$form[1];
            if (!in_array($formUid, $formsToSkip)) {
                $availableForms[$formUid] = $key;
            }
        }
        if (empty($availableForms)) {
            return;
        }

        $query = $this->connectionPool->getQueryBuilderForTable('tx_powermailcond_domain_model_conditioncontainer');
        $query->getRestrictions()->removeAll()->add(new DeletedRestriction());
        $query->select('form')
              ->distinct()
              ->from('tx_powermailcond_domain_model_conditioncontainer')
              ->where($query->expr()->in('form', array_keys($availableForms)));
        $existingForms = $query->executeQuery()->fetchFirstColumn();

        foreach ($existingForms as $uid) {
            $key = $availableForms[$uid];
            unset($params['items'][$key]);
        }
    }
}
