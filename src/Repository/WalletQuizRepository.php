<?php
namespace App\Repository;

use App\Entity\WalletQuiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WalletQuiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method WalletQuiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method WalletQuiz[]    findAll()
 * @method WalletQuiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WalletQuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WalletQuiz::class);
    }
    public function findQuizIdsByWalletId($walletId): array
    {
        return $this->createQueryBuilder('wq')
            ->select('wq.id_quiz')
            ->where('wq.id_wallet = :walletId')
            ->setParameter('walletId', $walletId)
            ->getQuery()
            ->getResult();
    }
    

    // Ajoutez ici des méthodes spécifiques au repository WalletQuiz
}
