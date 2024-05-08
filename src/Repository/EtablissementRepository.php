<?php

namespace App\Repository;

use App\Entity\Etablissement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Etablissement>
 *
 * @method Etablissement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etablissement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etablissement[]    findAll()
 * @method Etablissement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtablissementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etablissement::class);
    }
   
    public function isCodeEtablissementUnique(int $codeEtablissement, ?Etablissement $currentEtablissement = null): bool
{
    $existingEtablissement = $this->findOneBy(['code_etablissement' => $codeEtablissement]);

    // Si aucun établissement existant n'est trouvé, ou si l'établissement trouvé est le même que l'établissement actuel en cours de modification, le code est unique
    if ($existingEtablissement === null || ($currentEtablissement !== null && $existingEtablissement === $currentEtablissement)) {
        return true;
    }

    return false;
}

public function findByUserId(int $userId): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.utilisateur = :userId')//jointure m3a lvariable utilisateur fi lentity etablissement
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function getIDetablissementByCodeEtablissement(int $codeEtablissement): ?int
    {
        $result = $this->createQueryBuilder('e')
            ->select('e.id_etablissement')
            ->andWhere('e.code_etablissement = :code_etablissement')
            ->setParameter('code_etablissement', $codeEtablissement)
            ->getQuery()
            ->getOneOrNullResult();

        return $result ? $result['id_etablissement'] : null;
    }
 


//    /**
//     * @return Etablissement[] Returns an array of Etablissement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Etablissement
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
