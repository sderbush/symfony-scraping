<?php

namespace App\Service;

use App\Entity\Movie;
use Psr\Log\LoggerInterface;
use App\Repository\MovieRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class MovieManager
{
    public function __construct(
        private CacheInterface $cache,
        private LoggerInterface $logger,
        private MovieRepository $movieRepository,
        private string $ttl,
    ) {
    }

    /**
     * @param \DateTime $date
     * @return Movie[]
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getMoviesByDate(\DateTime $date): array
    {
        return $this->cache->get('movie.date.'.$date->format('ymd'), function (ItemInterface $item) use ($date) {
            $item->expiresAfter($this->ttl);
            $this->logger->debug('load movie by date: ' . $date->format('ymd'));
            return $this->movieRepository->findByDate($date);
        });

    }

    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getDates(): array
    {
        return $this->cache->get('movie.dates', function (ItemInterface $item) {
            $item->expiresAfter($this->ttl);
            $this->logger->debug('load movie dates');
            return $this->movieRepository->getDates();
        });
    }

}
