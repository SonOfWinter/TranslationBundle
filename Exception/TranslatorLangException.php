<?php

/**
 * TranslatorLangException
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Exception
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Exception;

/**
 * Class TranslatorLangException
 *
 * @package SOW\TranslationBundle\Exception
 */
class TranslatorLangException extends \Exception
{
    /**
     * TranslatorLangException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        if ($message == "") {
            $message = "Lang not in language list";
        }
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
