<?php

namespace App\Repository;

use App\Entity\Hotel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Hotel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hotel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hotel[]    findAll()
 * @method Hotel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hotel::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Hotel $entity, bool $flush = true): void
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
    public function remove(Hotel $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Hotel[] Returns an array of Hotel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    /*public function findOneBySomeField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.id in ( :data )')
            ->setParameter('data',$value)
            ->getQuery()
            ->getResult();
        ;
    }*/


    public function  findByList($id_list,$region){
        if($region!=='null'){
            if(sizeof($id_list)==0){
                return $this
                    ->createQueryBuilder('h')
                    ->Where('h.region =:region')
                    ->setParameter('region',$region)
                    ->getQuery()
                    ->getResult();
            }
            else{
                return $this
                    ->createQueryBuilder('h')
                    ->where('h.id in ( :data )')
                    ->andWhere('h.region =:region')
                    ->setParameter('data',$id_list)
                    ->setParameter('region',$region)
                    ->getQuery()
                    ->getResult();
            }
        }else{
            if(sizeof($id_list)==0){
                return $this
                    ->createQueryBuilder('h')
                    ->getQuery()
                    ->getResult();
            }
            else{
                return $this
                    ->createQueryBuilder('h')
                    ->where('h.id in ( :data )')
                    ->setParameter('data',$id_list)
                    ->getQuery()
                    ->getResult();
            }
        }

    }
}
