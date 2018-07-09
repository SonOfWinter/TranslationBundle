<?php

/**
 * TranslatorConfigurationException
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Exception
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Exception;

/**
 * Class TranslatorConfigurationException
 *
 * @package SOW\TranslationBundle\Exception
 */
class TranslatorConfigurationException extends \Exception
{
    /**
     * TranslatorConfigurationException constructor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        $message = "",
        $code = 0,
        \Throwable $previous = null
    ) {
        if ($message == "") {
            $message = "The Translator is not configured";
        }
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
