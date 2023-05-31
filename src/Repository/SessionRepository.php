<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Session>
 *
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function save(Session $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Session $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Requête permettant d'obtenir la liste des modules non programmés pour une session.
    public function findUnprogrammedModules(int $idSession, EntityManager $em){
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        // Selectionner tous les modules d'une session dont l'id est passé en paramètre.
        $qb->select('m')
            ->from('App\Entity\Module', 'm')
            ->leftJoin('m.programmes', 'p')
            ->where('p.session = :idSession');
        
        $sub = $em->createQueryBuilder();
        // Selectionner tous les modules qui ne sont pas dans le résultat précédent.
        $sub->select('mo')
            ->from('App\Entity\Module', 'mo')
            ->innerJoin('App\Entity\categorie', 'c')
            ->where($sub->expr()->notIn('mo.id', $qb->getDQL()))
            //requête paramétrée
            ->setParameter('idSession', $idSession)
            // Trier la liste des modules par catégorie
            ->orderBy('c.nom')
            ->orderBy('mo.nom');
        
        $query= $sub->getQuery();
        return $query->getResult();

        // $query = $em->createQuery('
        //     SELECT m
        //     FROM App\Entity\Module m
        //     INNER JOIN m.categorie c
        //     WHERE m.id NOT IN (
        //         SELECT m2.id
        //         FROM App\Entity\Module m2
        //         INNER JOIN App\Entity\Programme p WITH m2.id = p.module
        //         WHERE p.session = :sessionId
        //     )
        //     ORDER BY c.nom ASC, m.nom ASC')
        //     ->setParameter('sessionId', $idSession);
        //     return $query->getResult();
    }

    //Requête permettant d'obtenir la liste des stagiaires non inscrits à une session.
    public function findStudents(int $idSession){

        $em = $this->getEntityManager();
        $sub= $em->createQueryBuilder();

        $qb = $sub;
        //Sélectionner tous les stagiaires d'une session dont l'id est passé en paramètre

        $qb->select('s')
            ->from('App\entity\Stagiaire','s')
            ->leftJoin('s.sessions', 'se')
            ->where('se.id= :id');

        $sub = $em->createQueryBuilder();
        // sélectionner tous les stagiaires qui ne SONT PAS (NOT IN) dans le résultat précédent
        // On obtient donc les stagiaires non inscrits pour une session définie
        $sub->select('st')
            ->from('App\entity\Stagiaire','st')
            ->where($sub->expr()->notIn('st.id', $qb->getDQL()))
            //requête paramétrée
            ->setParameter('id', $idSession)
            // trier la liste des stagiaires sur le nom de famille
            ->orderBy('st.nom');

        $query =$sub->getQuery();
        return $query->getResult();

        // Ancienne version de la requête
        // $query= $em->createQuery('SELECT s
        //                             FROM App\Entity\Stagiaire s
        //                             WHERE s.id NOT IN(
        //                                 SELECT s2.id
        //                                 FROM App\Entity\Stagiaire s2
        //                                 INNER JOIN s2.sessions ss
        //                                 WHERE ss.id = :sessionId)
        //                                 ORDER BY s.nom ASC, s.prenom ASC')
        //     ->setParameter('sessionId', $idSession);
        //     return $query->getResult();
    }
//    /**
//     * @return Session[] Returns an array of Session objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Session
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
