<?php

declare(strict_types=1);

namespace In2code\PowermailCond\Controller;

use In2code\PowermailCond\Service\ConditionService;
use function json_encode;
use const JSON_THROW_ON_ERROR;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ConditionController extends ActionController
{
    protected ConditionService $conditionService;

    public function injectConditionService(ConditionService $conditionService): void
    {
        $this->conditionService = $conditionService;
    }

    /**
     * Build Condition for AJAX call
     *
     * @throws Throwable
     */
    public function buildConditionAction(): ResponseInterface
    {
        $requestBody = $this->request->getParsedBody();

        if (!isset($requestBody['tx_powermail_pi1'])) {
            $response = $this->jsonResponse(json_encode([ 'error' => 'Bad Request' ], JSON_THROW_ON_ERROR));
            $response = $response->withStatus(400);
            return $response;
        }

        $arguments = $this->conditionService->getArguments($requestBody['tx_powermail_pi1']);

        return $this->jsonResponse(json_encode($arguments, JSON_THROW_ON_ERROR));
    }
}
