<?php

declare(strict_types=1);

namespace In2code\PowermailCond\Exception;

use Exception;
use Throwable;

class MissingPowermailParameterException extends Exception
{
    private const MESSAGE = 'No powermail parameters are given - e.g. &tx_powermail_pi1[mail][form]=123';
    public const CODE = 1643809128416;

    public function __construct(Throwable $previous = null)
    {
        parent::__construct(self::MESSAGE, self::CODE, $previous);
    }
}
