<?php

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
