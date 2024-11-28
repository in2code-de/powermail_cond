<?php

namespace In2code\PowermailCond\UserFunc;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Domain\Model\Page;
use PDO;
use Throwable;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

class GetPowermailFields
{
    protected const DEFAULT_FIELD_TYPES = [
        'input',
        'textarea',
        'select',
        'radio',
        'check',
    ];

    protected ConnectionPool $connectionPool;

    public function __construct(ConnectionPool $connectionPool)
    {
        $this->connectionPool = $connectionPool;
    }

    /**
     * @throws Throwable
     */
    public function getFormFieldsForCondition(array &$params): void
    {
        $conditionContainer = $params['row']['conditioncontainer'] ?? '';
        if (!MathUtility::canBeInterpretedAsInteger($conditionContainer)) {
            return;
        }

        $query = $this->connectionPool->getQueryBuilderForTable('tx_powermailcond_domain_model_conditioncontainer');
        $query->getRestrictions()->removeByType(HiddenRestriction::class);
        $query->select('form')
              ->from('tx_powermailcond_domain_model_conditioncontainer')
              ->where($query->expr()->eq('uid', $query->createNamedParameter($conditionContainer, PDO::PARAM_INT)))
              ->setMaxResults(1);
        $formUid = $query->executeQuery()->fetchOne();

        $params = $this->getParamsForForm($params, $formUid);
    }

    /**
     * @throws Throwable
     */
    public function getFormFieldsForRule(array &$params): void
    {
        $conditions = $params['row']['conditions'] ?? '';
        if (!MathUtility::canBeInterpretedAsInteger($conditions)) {
            return;
        }

        $query = $this->connectionPool->getQueryBuilderForTable('tx_powermailcond_domain_model_conditioncontainer');
        $query->getRestrictions()->removeByType(HiddenRestriction::class);
        $query->select('cc.form')
              ->from('tx_powermailcond_domain_model_conditioncontainer', 'cc')
              ->leftJoin('cc', 'tx_powermailcond_domain_model_condition', 'c', 'cc.uid = c.conditioncontainer')
              ->where($query->expr()->eq('c.uid', $query->createNamedParameter($conditions, PDO::PARAM_INT)))
              ->andWhere($query->expr()->eq('c.hidden', $query->createNamedParameter(0)))
              ->andWhere($query->expr()->eq('c.deleted', $query->createNamedParameter(0)))
              ->setMaxResults(1);
        $formUid = $query->executeQuery()->fetchOne();

        $params = $this->getParamsForForm($params, $formUid);
    }

    /**
     * @throws Throwable
     */
    private function getParamsForForm(array $params, int $formUid): array
    {
        $fieldTypes = self::DEFAULT_FIELD_TYPES;
        if (!empty($params['config']['itemsProcFuncValue'])) {
            $fieldTypes = GeneralUtility::trimExplode(',', $params['config']['itemsProcFuncValue'], true);
        }

        $query = $this->connectionPool->getQueryBuilderForTable(Field::TABLE_NAME);
        foreach ($fieldTypes as $idx => $value) {
            $fieldTypes[$idx] = $query->quote($value);
        }
        $query->getRestrictions()->removeAll();
        $query->select('f.uid', 'f.title', 'f.marker')
              ->from(Field::TABLE_NAME, 'f')
              ->leftJoin('f', Page::TABLE_NAME, 'p', 'f.page = p.uid')
              ->leftJoin('p', Form::TABLE_NAME, 'form', 'p.form = form.uid')
              ->where($query->expr()->eq('f.hidden', $query->createNamedParameter(0)))
              ->andWhere($query->expr()->eq('f.deleted', $query->createNamedParameter(0)))
              ->andWhere($query->expr()->in('f.type', $fieldTypes))
              ->orderBy('f.sorting')
              ->setMaxResults(10000);
        if ($formUid > 0) {
            $query->andWhere($query->expr()->eq('form.uid', $formUid));
        }
        $fields = $query->executeQuery()->fetchAllAssociative();

        $params['items'][] = [
            'label' => 'powermail Fields',
            'value' => '--div--',
        ];
        foreach ($fields as $field) {
            $params['items'][] = [
                'label' => $field['title'] . ', {' . $field['marker'] . '}, uid' . $field['uid'],
                'value' => $field['uid'],
            ];
        }

        if (!empty($params['config']['itemsProcFunc_addFieldsets'])) {
            $query = $this->connectionPool->getQueryBuilderForTable(Page::TABLE_NAME);
            $query->select('uid', 'title')
                  ->from(Page::TABLE_NAME)
                  ->where($query->expr()->eq('form', $query->createNamedParameter($formUid, PDO::PARAM_INT)))
                  ->addOrderBy('sorting');
            $pages = $query->executeQuery()->fetchAllAssociative();
            $params['items'][] = [
                'label' => 'powermail Fieldsets',
                'value' => '--div--',
            ];
            foreach ($pages as $page) {
                $params['items'][] = [
                    'label' => $page['title'] . ' (' . $page['uid'] . ')',
                    'value' => 'fieldset:' . $page['uid'],
                ];
            }
        }
        return $params;
    }
}
