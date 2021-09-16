<?php

/**
 * Translation Bundle
 *
 * @package  SOW\TranslationBundle
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle;

use SOW\TranslationBundle\DependencyInjection\SOWTranslationExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class SOWTranslationBundle
 *
 * @package SOW\TranslationBundle
 */
class SOWTranslationBundle extends Bundle
{
    /**
     * @return SOWTranslationExtension
     */
    public function getContainerExtension()
    {
        return new SOWTranslationExtension();
    }
}
