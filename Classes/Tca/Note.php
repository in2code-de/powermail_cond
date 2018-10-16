<?php
namespace In2code\PowermailCond\Tca;

use Doctrine\DBAL\DBALException;
use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Domain\Model\Page;
use In2code\Powermail\Utility\DatabaseUtility;

/**
 * Show a note if a form is chosen that has too much fields
 *
 * Class Note
 */
class Note
{

    /**
     * @var \TYPO3\CMS\Lang\LanguageService
     */
    protected $languageService = null;

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
     * @param array $params
     * @return string
     * @throws DBALException
     */
    public function showNote(array $params)
    {
        $this->initialize();
        $content = '';
        if ($this->formHasTooMuchFields($params)) {
            $content = '<div class="alert alert-warning"><strong>' . $this->getTitle() . '</strong> ' .
                $this->getDescription() . '</div>';
        }
        return $content;
    }

    /**
     * @param array $params
     * @return bool
     * @throws DBALException
     */
    protected function formHasTooMuchFields(array $params)
    {
        $formUid = (int)$params['row']['form'][0];
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
            'left join ' . Page::TABLE_NAME . ' p on f.pages = p.uid ' .
            'left join ' . Form::TABLE_NAME . ' form on p.forms = form.uid';
        $query .=  ' where form.uid = ' . (int) $formUid . ' and f.hidden = 0 and f.deleted = 0 ' .
            'and p.hidden = 0 and p.deleted = 0';
        return (int)$connection->executeQuery($query)->fetchColumn(0);
    }

    /**
     * @return string
     */
    protected function getTitle()
    {
        return $this->languageService->sL(
            $this->locallangPath . 'tx_powermailcond_conditioncontainer.note.title',
            true
        );
    }

    /**
     * @return string
     */
    protected function getDescription()
    {
        return $this->languageService->sL(
            $this->locallangPath . 'tx_powermailcond_conditioncontainer.note.description',
            true
        );
    }

    /**
     * Initialize some variables
     *
     * @return void
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected function initialize()
    {
        $this->languageService = $GLOBALS['LANG'];
    }
}
