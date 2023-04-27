<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function insertMoviesByDate($data, $date): void
    {
        $this->deleteMoviesByDate($date);

        foreach ($data as $movieData) {
            $movie = (new Movie())
                ->setPosition($movieData['position'])
                ->setTitle($movieData['title'])
                ->setRating($movieData['rating'])
                ->setYear($movieData['year'])
                ->setCount($movieData['count'])
                ->setDate($date);
            $this->getEntityManager()->persist($movie);
        }

        $this->getEntityManager()->flush();
    }

    public function deleteMoviesByDate($date): mixed
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->delete(Movie::class, 'm')
            ->where('m.date = :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->execute();
    }

    /**
     * @return Movie[] Returns an array of Movie objects
     */
    public function findByDate($date): array
    {
        return $this->findBy(['date' => $date], ['position' => 'asc']);
    }

    /**
     * @return string[] Returns an array of date
     */
    public function getDates(): array
    {
        return $this->createQueryBuilder('m')
            ->distinct()
            ->select('m.date')
            ->orderBy('m.date', 'desc')
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_SCALAR_COLUMN);
    }

}
