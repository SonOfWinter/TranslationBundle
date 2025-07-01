<?php

namespace SOW\TranslationBundle\Exception;

use Exception;
use Throwable;

/**
 * Class TranslatorConfigurationException
 *
 * @package SOW\TranslationBundle\Exception
 */
class TranslatorConfigurationException extends Exception
{
    /**
     * TranslatorConfigurationException constructor.
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
            $message = "The Translator is not configured";
        }
        parent::__construct($message, $code, $previous);
    }
}
