<?php

declare(strict_types=1);

namespace In2code\PowermailCond\Exception;

use Exception;
use Throwable;

class UnsupportedVariableTypeException extends Exception
{
    private const MESSAGE = 'A value for a field can only be array or string';
    public const CODE = 1588236757;

    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(self::MESSAGE, self::CODE, $previous);
    }
}
