<?php

namespace App\Repository;

use App\Entity\Partida;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Partida>
 *
 * @method Partida|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partida|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partida[]    findAll()
 * @method Partida[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartidaRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct($registry, Partida::class);
    }

    public function findGameById(int $id): ?Partida
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
    public function setGameInfo(Partida $partida, User $user1, User $user2,  ): void
    {
        $partida->setJugador1($user1);
        $partida->setJugador2($user2);
        $partida->setEstado('starting');
        $this->em->persist($partida);
        $this->em->flush();
    }
    public function setInGame(Partida $partida)
    {
        $partida->setEstado('in-game');
        $this->em->flush();
    }
    public function setFinish(Partida $partida)
    {
        $partida->setEstado('finish');
        $this->em->flush();
    }

    public function getPoints(Partida $partida, $id)
    {
        if($id == $partida->getJugador1()->getId())
        {
            return $partida->getPuntuajeJugador1();
        }
        else if ($id == $partida->getJugador2()->getId())
        {
            return $partida->getPuntuajeJugador2();
        }
        else
            return null;
    }
    public function sumaPuntos(Partida $partida, $id, $respuesta)
    {
        $jugador1Id = $partida->getJugador1()->getId();
        $jugador2Id = $partida->getJugador2()->getId();

        if ($id === $jugador1Id) {
            $puntuajeJugador1 = $this->getPoints($partida, $jugador1Id);

            if ($respuesta === 'correcta') {
                $partida->setPuntuajeJugador1($puntuajeJugador1 + 1);
            } else if ($puntuajeJugador1 !== null && $puntuajeJugador1 += 0 && $respuesta === 'incorrecta') {
                $partida->setPuntuajeJugador1($puntuajeJugador1 - 1);
            } else {
                return null;
            }
        } else if ($id === $jugador2Id) {
            $puntuajeJugador2 = $this->getPoints($partida, $jugador2Id);

            if ($respuesta === 'correcta') {
                $partida->setPuntuajeJugador2($puntuajeJugador2 + 1);
            } else if ($puntuajeJugador2 !== null && $respuesta === 'incorrecta') {
                $partida->setPuntuajeJugador2($puntuajeJugador2 - 1);
            } else {
                return null;
            }
        }
        $this->getEntityManager()->flush();
    }
    public function updateQuestionPosition(Partida $partida, $id): ?string
    {
        $jugador1Id = $partida->getJugador1()->getId();
        $jugador2Id = $partida->getJugador2()->getId();
        $j1 = $partida->getPreguntaJugador1();
        $j2 = $partida->getPreguntaJugador2();

        if($jugador1Id === $id)
        {
            if($j1 < 10)
            {
                $partida->setPreguntaJugador1($partida->getPreguntaJugador1()+1);
                $this->getEntityManager()->flush();
                return null;
            }
            else if($j1 = 10 && $j2 = 10){
                return $this->getWiner($partida);
            }
            return null;
        }
        else if($jugador2Id === $id)
        {
            if($j2 < 10)
            {
                $partida->setPreguntaJugador2($partida->getPreguntaJugador2()+1);
                $this->getEntityManager()->flush();
                return null;
            }
            else if($j2 = 10 && $j2 = 10){
                return $this->getWiner($partida);
            }
            return null;
        }
        return null;
    }
    public function getQuestionPosition(Partida $partida, $id)
    {
        if($id == $partida->getJugador1()->getId())
            return $partida->getPreguntaJugador1();
        else if ($id == $partida->getJugador2()->getId())
            return $partida->getPreguntaJugador2();
        else
            return null;
    }
    public function getWiner(Partida $partida)
    {
        $p1 = $partida->getPuntuajeJugador1();
        $p2 = $partida->getPuntuajeJugador2();
        if($p1 > $p2){
            return 'El ganador de la partida es '.$partida->getJugador1()->getName(). ' Con '. $p1. ' Puntos!'.`</br>`.' El jugador '.$partida->getJugador2()->getName(). ' ha conseguido '
                .$p2.' Puntos!';
        }
        elseif($p1 < $p2){
            return 'El ganador de la partida es '.$partida->getJugador2()->getName(). ' Con '. $p2. ' Puntos!'.`</br>`.' El jugador '.$partida->getJugador1()->getName(). ' ha conseguido '
                .$p1.' Puntos!';
        }
        else
        {
            return 'Parece que ha habido un empate entre los jugadores! '.`</br>`. $partida->getJugador1()->getName(). $p1.`</br>`.$partida->getJugador2()->getName(). $p2. `</br>`;
        }
    }

    //    /**
    //     * @return Partida[] Returns an array of Partida objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Partida
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
