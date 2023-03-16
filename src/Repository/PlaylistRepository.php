<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Playlist>
 *
 * @method Playlist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Playlist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Playlist[]    findAll()
 * @method Playlist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaylistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    public function add(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    /**
    * Retourne toutes les playlists triées sur le nom de la playlist
    * @param type $champ
    * @param type $ordre
    * @return Playlist[]
    */
    public function findAllOrderByName($ordre): array{
        return $this->createQueryBuilder('p')
        ->leftjoin('p.formations', 'f')
        ->groupBy('p.id')
        ->orderBy('p.name', $ordre)
        ->getQuery()
        ->getResult();
    }
    /**
    * Retourne toutes les playlists triées sur le nombre de formations
    * @param type $ordre
    * @return Playlist[]
    */
    public function findAllOrderByNbFormations($ordre): array{
        return $this->createQueryBuilder('p')
        ->leftjoin('p.formations', 'f')
        ->groupBy('p.id')
        ->orderBy('count(f.title)', $ordre)
        ->getQuery()
        ->getResult();
    } 

   public function findByContainValueInPlaylist($champ, $valeur): array
{
    if ($valeur == "") {
        return $this->findAllOrderByName('ASC');
    }

    return $this->createQueryBuilder('p')
        ->leftJoin('p.formations', 'f')
        ->where('p.' . $champ . ' LIKE :valeur')
        ->setParameter('valeur', '%' . $valeur . '%')
        ->groupBy('p.id')
        ->orderBy('p.name', 'ASC')
        ->getQuery()
        ->getResult();
}

public function findByContainValueInCategories($champ, $valeur): array
{
    if ($valeur == "") {
        return $this->findAllOrderByName('ASC');
    }

    return $this->createQueryBuilder('p')
        ->leftJoin('p.formations', 'f')
        ->leftJoin('f.categories', 'c')
        ->where('c.' . $champ . ' LIKE :valeur')
        ->setParameter('valeur', '%' . $valeur . '%')
        ->groupBy('p.id')
        ->orderBy('p.name', 'ASC')
        ->getQuery()
        ->getResult();
}

   
}
