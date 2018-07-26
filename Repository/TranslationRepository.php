<?php

/**
 * Translation Repository
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Annotation
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use SOW\TranslationBundle\Entity\Translatable;

/**
 * Class TranslationRepository
 *
 * @package SOW\TranslationBundle\Repository
 */
class TranslationRepository extends EntityRepository implements TranslationRepositoryInterface
{
    /**
     * TranslationRepository constructor.
     *
     * @param EntityManagerInterface $em
     * @param string $translationClassName
     */
    public function __construct(EntityManagerInterface $em, string $translationClassName)
    {
        $classMetaData = new ClassMetadata($translationClassName);
        parent::__construct($em, $classMetaData);
    }

    /**
     * find all by object and langs
     *
     * @param Translatable $translatable
     * @param array $langs
     *
     * @return mixed
     */
    public function findAllByObjectAndLangs(
        Translatable $translatable,
        array $langs
    ) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('t');
        $qb->from(
            $this->_entityName,
            't'
        )->where('t.`entityName` : :entityName')
            ->andWhere('t.`entityId` : :entityId')
            ->andWhere('t.`lang` IN (:langs)')
            ->orderBy(
                't.`key`',
                'ASC'
            )
            ->setParameters(
                [
                    "entityName" => $translatable->getEntityName(),
                    "entityId" => $translatable->getId(),
                    "langs" => $langs
                ]
            );
        return $qb->getQuery()->getResult();
    }
}
