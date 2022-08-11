<?php

declare(strict_types=1);

namespace In2code\PowermailCond\Domain\Repository;

use In2code\PowermailCond\Domain\Model\ConditionContainer;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @method ConditionContainer|null findOneByForm(int $form)
 */
class ConditionContainerRepository extends Repository
{
    public function initializeObject(): void
    {
        $querySettings = $this->objectManager->get(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $querySettings->setRespectSysLanguage(false);
        $this->setDefaultQuerySettings($querySettings);
    }
}
