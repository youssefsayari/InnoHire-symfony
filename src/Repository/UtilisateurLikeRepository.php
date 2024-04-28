<?php

namespace App\Repository;

use App\Entity\UtilisateurLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UtilisateurLike>
 *
 * @method UtilisateurLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method UtilisateurLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method UtilisateurLike[]    findAll()
 * @method UtilisateurLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UtilisateurLike::class);
    }
    public function addUserLike($idPost, $idUtilisateur): void
    {
        // Vérifier si l'utilisateur a déjà aimé le post
        $entityManager = $this->getEntityManager();
        $query = "
            SELECT COUNT(*) 
            FROM utilisateur_like 
            WHERE id_post = :idPost AND id_utilisateur = :idUtilisateur
        ";
        $result = $entityManager->getConnection()->fetchOne($query, [
            'idPost' => $idPost,
            'idUtilisateur' => $idUtilisateur
        ]);
        
        // Si l'utilisateur a déjà aimé le post, ne rien faire
        if ($result > 0) {
            return;
        }
    
        // Si l'utilisateur n'a pas encore aimé le post, ajouter le like
        $query = "
            INSERT INTO utilisateur_like (id_post, id_utilisateur)
            VALUES (:idPost, :idUtilisateur)
        ";
        
        // Exécution de la requête avec les paramètres
        $entityManager->getConnection()->executeStatement($query, [
            'idPost' => $idPost,
            'idUtilisateur' => $idUtilisateur
        ]);
    }
    public function isPostLikedByUser(int $postId, int $userId): bool
    {
        return $this->createQueryBuilder('ul')
            ->select('COUNT(ul.id)')
            ->andWhere('ul.id_post = :postId')
            ->andWhere('ul.id_utilisateur = :userId')
            ->setParameter('postId', $postId)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }
    public function removeUserLike(int $postId, int $userId): void
{
    $entityManager = $this->getEntityManager();
    $query = "
        DELETE FROM utilisateur_like 
        WHERE id_post = :postId AND id_utilisateur = :userId
    ";
    $entityManager->getConnection()->executeStatement($query, [
        'postId' => $postId,
        'userId' => $userId
    ]);
}


//    /**
//     * @return UtilisateurLike[] Returns an array of UtilisateurLike objects
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

//    public function findOneBySomeField($value): ?UtilisateurLike
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
