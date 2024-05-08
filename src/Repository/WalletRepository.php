<?php

namespace App\Repository;

use App\Entity\Wallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Types\Types;


/**
 * @extends ServiceEntityRepository<Wallet>
 *
 * @method Wallet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wallet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wallet[]    findAll()
 * @method Wallet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WalletRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wallet::class);
    }
    public function findById(int $id): ?Wallet
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.id_wallet = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function getIDwalletbyIDEtablissement(int $idEtablissement): ?int
    {
        $result = $this->createQueryBuilder('w')
            ->select('w')
            ->andWhere('w.etablissement = :id_etablissement')
            ->setParameter('id_etablissement', $idEtablissement)
            ->getQuery()
            ->getOneOrNullResult();

        return $result ? $result->getId() : null;
    }
  

    /**
     * Vérifie si l'établissement est unique dans la table Wallet.
     *
     * @param int $idEtablissement L'identifiant de l'établissement à vérifier.
     *
     * @return bool Vrai si l'établissement est unique, faux sinon.
     */
    public function etablissementExists($idEtablissement): bool
    {
        $qb = $this->createQueryBuilder('w');
        $qb->select('COUNT(w.id_wallet)');
        $qb->andWhere('w.etablissement = :idEtablissement');
        $qb->setParameter('idEtablissement', $idEtablissement);
        
        $count = $qb->getQuery()->getSingleScalarResult();
        
        return intval($count) > 0; // Retourne true si le compte est supérieur à 0, sinon false
    }

   
    
    
    

    
}
