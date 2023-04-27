<?php

namespace App\Command;

use App\Entity\Movie;
use App\Service\MovieScraper;
use App\Repository\MovieRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:get-movies',
    description: 'Add a short description for your command',
)]
class GetMoviesCommand extends Command
{
    public function __construct(
        private MovieScraper $movieScraper,
        private MovieRepository $movieRepository,
        string $name = null)
    {
        parent::__construct($name);
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $currentDate = new \DateTime();
        $io->info("Получаем фильмы (дата: ". $currentDate->format('Y-m-d') . ")");

        $moviesData = $this->movieScraper->loadTop10();
        if ($moviesData === null) {
            return Command::FAILURE;
        }

        $this->movieRepository->insertMoviesByDate($moviesData, $currentDate);
        $io->success('Loaded ' . count($moviesData) . ' movies');

        return Command::SUCCESS;
    }
}
