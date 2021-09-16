<?php

/**
 * Translation class
 *
 * @package  SOW\TranslationBundle\Entity
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class AbstractTranslation
 *
 * @package SOW\TranslationBundle\Entity
 *
 * @ORM\Table(name="`sow_translation`")
 * @ORM\Entity(repositoryClass="SOW\TranslationBundle\Repository\TranslationRepository")
 */
class Translation extends AbstractTranslation
{
}
