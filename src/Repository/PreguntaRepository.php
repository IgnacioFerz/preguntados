<?php

namespace App\Repository;

use App\Entity\Pregunta;
use App\Service\GetQuestionsFromApi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pregunta>
 *
 * @method Pregunta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pregunta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pregunta[]    findAll()
 * @method Pregunta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreguntaRepository extends ServiceEntityRepository
{
    private GetQuestionsFromApi $getQuestionsFromApi;
    private EntityManagerInterface $entityManager;
    public function __construct(ManagerRegistry $registry, GetQuestionsFromApi $getQuestionsFromApi, EntityManagerInterface $entityManager)
    {
        $this->getQuestionsFromApi = $getQuestionsFromApi;
        $this->entityManager = $entityManager;
        parent::__construct($registry, Pregunta::class);
    }
    public function searchForGameId($id) :array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.partida = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
            ;
    }
    public function addQuestions(Pregunta $pregunta, $partida,$preguntaData)
    {
        $pregunta->setPartida($partida);
        $pregunta->setPregunta($preguntaData['texto']);
        $pregunta->setRespuestaCorrecta($this->getQuestionsFromApi->getCorrectAnswer($preguntaData['respuestas']));
        $pregunta->setRespuestasIncorrectas($this->getQuestionsFromApi->getIncorrectAnswer($preguntaData['respuestas']));
        $this->entityManager->persist($pregunta);
        $this->entityManager->flush();
    }
    //    /**
    //     * @return Pregunta[] Returns an array of Pregunta objects
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

    //    public function findOneBySomeField($value): ?Pregunta
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
