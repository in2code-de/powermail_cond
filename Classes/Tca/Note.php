<?php
namespace In2code\PowermailCond\Tca;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Domain\Model\Page;

/**
 * Show a note if a form is chosen that has too much fields
 * 
 * Class Note
 * @package In2code\PowermailCond\Tca
 */
class Note
{

    /**
     * @var \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected $databaseConnection = null;

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
     */
    protected function formHasTooMuchFields(array $params)
    {
        $formUid = (int)$params['row']['form'][0];
        return $formUid > 0 && $this->getNumberOfFieldsToForm($formUid) > $this->fieldLimit;
    }

    /**
     * @param int $formUid
     * @return int
     */
    protected function getNumberOfFieldsToForm($formUid)
    {
        $select = 'count(f.uid) sum';
        $from = Field::TABLE_NAME . ' f ' .
            'left join ' . Page::TABLE_NAME . ' p on f.pages = p.uid ' .
            'left join ' . Form::TABLE_NAME . ' form on p.forms = form.uid';
        $where = 'form.uid = ' . (int) $formUid . ' and f.hidden = 0 and f.deleted = 0 ' .
            'and p.hidden = 0 and p.deleted = 0';
        $res = $this->databaseConnection->exec_SELECTquery($select, $from, $where);
        if ($res) {
            $row = $this->databaseConnection->sql_fetch_assoc($res);
            return (int)$row['sum'];
        }
        return 0;
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
        $this->databaseConnection = $GLOBALS['TYPO3_DB'];
    }
}
