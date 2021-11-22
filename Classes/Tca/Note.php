<?php
declare(strict_types = 1);
namespace In2code\PowermailCond\Tca;

use Doctrine\DBAL\DBALException;
use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Domain\Model\Page;
use In2code\Powermail\Utility\DatabaseUtility;
use In2code\Powermail\Utility\ObjectUtility;
use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;

/**
 * Class Note
 * to show a note if a form is chosen that has too much fields
 */
class Note extends AbstractFormElement
{
    /**
     * Path to locallang file (with : as postfix)
     *
     * @var string
     */
    protected $locallangPath = 'LLL:EXT:powermail_cond/Resources/Private/Language/locallang_db.xml:';

    /**
     * @var int
     */
    protected $fieldLimit = 30;

    /**
     * @return array
     * @throws DBALException
     */
    public function render()
    {
        $content = '';
        if ($this->formHasTooMuchFields()) {
            $content = '<div class="alert alert-warning"><strong>' . $this->getTitle() . '</strong> ' .
                $this->getDescription() . '</div>';
        }
        return ['html' => $content];
    }

    /**
     * @return bool
     * @throws DBALException
     */
    protected function formHasTooMuchFields()
    {
        $formUid = (int)$this->data['databaseRow']['form'][0];
        return $formUid > 0 && $this->getNumberOfFieldsToForm($formUid) > $this->fieldLimit;
    }

    /**
     * @param $formUid
     * @return int
     * @throws DBALException
     */
    protected function getNumberOfFieldsToForm($formUid)
    {
        $connection = DatabaseUtility::getConnectionForTable(Field::TABLE_NAME);
        $query = 'select count(f.uid) sum';
        $query .= ' from ' . Field::TABLE_NAME . ' f ' .
            'left join ' . Page::TABLE_NAME . ' p on f.page = p.uid ' .
            'left join ' . Form::TABLE_NAME . ' form on p.form = form.uid';
        $query .=  ' where form.uid = ' . (int)$formUid . ' and f.hidden = 0 and f.deleted = 0 ' .
            'and p.hidden = 0 and p.deleted = 0';
        return (int)$connection->executeQuery($query)->fetchColumn();
    }

    /**
     * @return string
     */
    protected function getTitle()
    {
        $languageService = ObjectUtility::getLanguageService();
        return $languageService->sL($this->locallangPath . 'tx_powermailcond_conditioncontainer.note.title');
    }

    /**
     * @return string
     */
    protected function getDescription()
    {
        $languageService = ObjectUtility::getLanguageService();
        return $languageService->sL($this->locallangPath . 'tx_powermailcond_conditioncontainer.note.description');
    }
}
