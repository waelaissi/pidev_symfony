<?php

namespace App\Repository;

use App\Entity\Maison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Maison|null find($id, $lockMode = null, $lockVersion = null)
 * @method Maison|null findOneBy(array $criteria, array $orderBy = null)
 * @method Maison[]    findAll()
 * @method Maison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Maison::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Maison $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Maison $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Maison[] Returns an array of Maison objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Maison
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function  findByList($id_list,$region){
        if($region!=='null'){
            if(sizeof($id_list)==0){
                return $this
                    ->createQueryBuilder('h')
                    ->Where('h.region = :region')
                    ->setParameter('region',$region)
                    ->getQuery()
                    ->getResult();
            }else{
                return $this
                    ->createQueryBuilder('h')
                    ->where('h.id not in ( :data )')
                    ->andWhere('h.region = :region')
                    ->setParameter('data',$id_list)
                    ->setParameter('region',$region)
                    ->getQuery()
                    ->getResult();
            }
        }
        else{
            if(sizeof($id_list)==0){
                return $this
                    ->createQueryBuilder('h')
                    ->getQuery()
                    ->getResult();
            }else{
                return $this
                    ->createQueryBuilder('h')
                    ->where('h.id not in ( :data )')
                    ->setParameter('data',$id_list)
                    ->getQuery()
                    ->getResult();
            }
        }

    }
}
