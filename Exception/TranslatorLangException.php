<?php

namespace SOW\TranslationBundle\Exception;

use Exception;
use Throwable;

/**
 * Class TranslatorLangException
 *
 * @package SOW\TranslationBundle\Exception
 */
class TranslatorLangException extends Exception
{
    /**
     * TranslatorLangException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        Throwable $previous = null
    ) {
        if ($message == "") {
            $message = "Lang not in language list";
        }
        parent::__construct($message, $code, $previous);
    }
}
