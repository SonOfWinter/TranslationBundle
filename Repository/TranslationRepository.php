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
use SOW\TranslationBundle\Entity\Translatable;

/**
 * Class TranslationRepository
 *
 * @package SOW\TranslationBundle\Repository
 */
class TranslationRepository implements TranslationRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var string
     */
    protected $translationClassName;

    /**
     * TranslationRepository constructor.
     *
     * @param EntityManagerInterface $em
     * @param string $translationClassName
     */
    public function __construct(EntityManagerInterface $em, string $translationClassName)
    {
        $this->em = $em;
        $this->translationClassName = $translationClassName;
    }

    /**
     * Find one element with search array
     *
     * @param array $data
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return mixed
     */
    public function findOneBy(array $data)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('t')
            ->from(
                $this->translationClassName,
                't'
            );
        $parameterCount = 0;
        foreach ($data as $key => $value) {
            $dataValue = "value{$parameterCount}";
            $qb->andWhere($qb->expr()->eq("t.{$key}", ":{$dataValue}"));
            $qb->setParameter($dataValue, $value);
            $parameterCount++;
        }
        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * Find all elements with search array
     *
     * @param array $data
     * @param array $orderBy
     *
     * @return mixed
     */
    public function findBy(array $data, array $orderBy = [])
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('t')
            ->from(
                $this->translationClassName,
                't'
            );
        $parameterCount = 0;
        foreach ($data as $key => $value) {
            $dataValue = "value{$parameterCount}";
            $qb->andWhere($qb->expr()->eq("t.{$key}", ":{$dataValue}"));
            $qb->setParameter($dataValue, $value);
            $parameterCount++;
        }
        foreach ($orderBy as $property => $order) {
            $qb->orderBy($property, $order);
        }
        return $qb->getQuery()->getResult();
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
        $qb = $this->em->createQueryBuilder();
        $qb->select('t');
        $qb->from(
            $this->translationClassName,
            't'
        )->where('t.entityName : :entityName')->andWhere('t.entityId : :entityId')->andWhere('t.lang IN (:langs)')->orderBy(
            't.key',
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
