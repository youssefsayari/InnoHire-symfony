<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 *
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }
    public function findUserByCredentials(string $cin, string $mdp): ?Utilisateur
    {
        return $this->findOneBy(['cin' => $cin, 'mdp' => $mdp]);
    }
    public function findBySearchAndSort($searchQuery, $sortOrder)
    {
        $queryBuilder = $this->createQueryBuilder('u');
    
        // If a search query is provided, add a condition to filter by Cin
        if ($searchQuery) {
            $queryBuilder->andWhere('u.cin LIKE :search')
                         ->setParameter('search', '%' . $searchQuery . '%');
        }
    
        // If a sort order is provided, add an ORDER BY clause based on the sort order
        if ($sortOrder === 'asc') {
            $queryBuilder->orderBy('u.cin', 'ASC');
        } elseif ($sortOrder === 'desc') {
            $queryBuilder->orderBy('u.cin', 'DESC');
        }
    
        // Execute the query and return the results
        return $queryBuilder->getQuery()->getResult();
    }
    public function findOneByCin(String $cin): ?Utilisateur
    {
        return $this->findOneBy(['cin' => $cin]);
    }
    public function updateOTP(Utilisateur $user, $otp)
    {
        $entityManager = $this->getEntityManager();
        $user->setOTP($otp);
        $entityManager->persist($user);
        $entityManager->flush();
    }
    public function countUsersByRole()
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id_utilisateur) as userCount, u.role')
            ->groupBy('u.role')
            ->getQuery()
            ->getResult();
    }
    public function countTotalUsers(): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id_utilisateur) as totalUsers')
            ->getQuery()
            ->getSingleScalarResult();
    }


//    /**
//     * @return Utilisateur[] Returns an array of Utilisateur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Utilisateur
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
