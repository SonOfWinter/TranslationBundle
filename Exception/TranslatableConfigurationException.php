<?php

namespace SOW\TranslationBundle\Exception;

use Exception;
use Throwable;

/**
 * Class TranslatableConfigurationException
 *
 * @package SOW\TranslationBundle\Exception
 */
class TranslatableConfigurationException extends Exception
{
    /**
     * TranslatableConfigurationException constructor.
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
            $message = "The Entity is misconfigured";
        }
        parent::__construct($message, $code, $previous);
    }
}
