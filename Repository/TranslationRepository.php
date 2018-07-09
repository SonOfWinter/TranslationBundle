<?php

/**
 * Translation repository
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Repository
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use SOW\TranslationBundle\Entity\Translatable;
use SOW\TranslationBundle\Entity\Translation;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class TranslationRepository
 *
 * @package SOW\TranslationBundle\Repository
 */
class TranslationRepository extends ServiceEntityRepository implements TranslationRepositoryInterface
{
    /**
     * TranslationRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct(
            $registry,
            Translation::class
        );
    }

    /**
     * find all by object and langs
     *
     * @param Translatable $translatable
     * @param array        $langs
     *
     * @return void
     */
    public function findAllByObjectAndLangs(
        Translatable $translatable,
        array $langs
    ) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t')
            ->from(
                Translation::class,
                't'
            )
            ->where('t.entityName : :entityName')
            ->andWhere('t.entityId : :entityId')
            ->andWhere('t.lang IN (:langs)')
            ->orderBy(
                't.key',
                'ASC'
            )
            ->setParameters(
                [
                    "entityName" => $translatable->getEntityName(),
                    "entityId"   => $translatable->getId(),
                    "langs"      => $langs
                ]
            )->getQuery()->getResult();
    }
}
