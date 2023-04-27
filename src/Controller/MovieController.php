<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use App\Service\MovieManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/', name: 'app_movie_index', methods: ['GET'])]
    public function index(Request $request, MovieManager $movieManager): Response
    {
        $allDates = $movieManager->getDates();
        $date = $allDates[0];

        $form = $this->createForm(MovieType::class, $allDates, ['method' => 'GET']);
        $form->handleRequest($request);

        $date = new \DateTime($form->getData()['date'] ?? $date);

        return $this->render('movie/index.html.twig', [
            'movies' => $movieManager->getMoviesByDate($date),
            'form' => $form,
            'date' => $date
        ]);
    }

    #[Route('/{id}/edit', name: 'app_movie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Movie $movie, MovieRepository $movieRepository): Response
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movieRepository->save($movie, true);

            return $this->redirectToRoute('app_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

}
