<?php

declare(strict_types=1);

namespace In2code\PowermailCond\Utility;

use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

class ConfigurationUtility
{
    public static function getConditionLoopCount(): int
    {
        $extConfigTemplatesSettings = self::getExtConfTemplateSettings();

        if (!isset($extConfigTemplatesSettings['conditionLoopCount']) || !MathUtility::canBeInterpretedAsInteger($extConfigTemplatesSettings['conditionLoopCount'])) {
            return 100;
        }

        return (int)$extConfigTemplatesSettings['conditionLoopCount'];
    }

    protected static function getExtConfTemplateSettings(): array
    {
        try {
            $configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('powermail_cond');
            return $configuration ?? [];
        } catch (ExtensionConfigurationExtensionNotConfiguredException | ExtensionConfigurationPathDoesNotExistException $e) {
            return [];
        }
    }
}
