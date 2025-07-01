<?php

namespace SOW\TranslationBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use SOW\TranslationBundle\Entity\AbstractTranslation;
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
     * @return AbstractTranslation[]
     */
    public function findAllByObjectAndLangs(Translatable $translatable, array $langs): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t');
        $qb->from($this->getEntityName(), 't')
            ->where('t.entityName = :entityName')
            ->andWhere('t.entityId = :entityId')
            ->andWhere('t.lang IN (:langs)')
            ->orderBy('t.key', 'ASC')
            ->setParameters(
                new ArrayCollection(
                    [
                        "entityName" => $translatable->getEntityName(),
                        "entityId" => $translatable->getId(),
                        "langs" => $langs,
                    ]
                )
            );
        return $qb->getQuery()->getResult();
    }

    /**
     * findAllByEntityNameAndLang
     *
     * @param string $entityName
     * @param array $ids
     * @param string $lang
     *
     * @return AbstractTranslation[]
     */
    public function findAllByEntityNameAndLang(string $entityName, array $ids, string $lang): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t');
        $qb->from($this->getEntityName(), 't')
            ->where('t.entityName = :entityName')
            ->andWhere('t.lang = :lang')
            ->andWhere('t.entityId IN (:ids)')
            ->orderBy('t.entityId', 'ASC')
            ->setParameters(
                new ArrayCollection([
                    "entityName" => $entityName,
                    "lang" => $lang,
                    "ids" => $ids,
                ])
            );
        return $qb->getQuery()->getResult();
    }
}
