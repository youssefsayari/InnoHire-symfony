<?php
namespace App\Repository;

use App\Entity\QuizUtilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 *  @extends ServiceEntityRepository<Messagerie>
 *
 * @method QuizUtilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizUtilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizUtilisateur    findAll()
 * @method QuizUtilisateur    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizUtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizUtilisateur::class);
    }

    // Ajoutez ici vos propres méthodes de requête personnalisées si nécessaire
}
