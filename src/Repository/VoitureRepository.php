<?php

namespace App\Repository;

use App\Entity\Voiture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Voiture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voiture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voiture[]    findAll()
 * @method Voiture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voiture::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Voiture $entity, bool $flush = true): void
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
    public function remove(Voiture $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Voiture[] Returns an array of Voiture objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Voiture
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
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
                    ->createQueryBuilder('c')
                    ->Where('u.adresse = :region')
                    ->join('c.idUser','u')
                    ->setParameter('region',$region)
                    ->getQuery()
                    ->getResult();
            }else{
                return $this
                    ->createQueryBuilder('c')
                    ->where('c.id not in ( :data )')
                    ->andWhere('u.adresse = :region')
                    ->join('c.idUser','u')
                    ->setParameter('data',$id_list)
                    ->setParameter('region',$region)
                    ->getQuery()
                    ->getResult();
            }
        }else{
            if(sizeof($id_list)==0){
                return $this
                    ->createQueryBuilder('c')
                    ->getQuery()
                    ->getResult();
            }else{
                return $this
                    ->createQueryBuilder('c')
                    ->where('c.id NOT IN ( :data )')
                    ->setParameter('data',$id_list)
                    ->getQuery()
                    ->getResult();
            }
        }


    }
}
