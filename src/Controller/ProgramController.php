<?php

// src/Controller/ProgramController.php

namespace App\Controller;

use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this->render('program/index.html.twig', [
            'programs' => $programs,
         ]);
    }

    #[Route('/show/{id}', methods: ['GET'], requirements: ['id'=>'^\d+$'], name: 'show')]
    public function show(int $id, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }
        $seasons = $seasonRepository->findBy(['program_id' => $program]);
        if (!$seasons) {
            $noSeasonFound = 'Aucune saison trouvée';
        } else {
            $noSeasonFound = '';
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
            'noSeasonFound' => $noSeasonFound,
        ]);
    }

    #[Route('/{programId}/season/{seasonId}', methods: ['GET'], requirements: ['seasonId' =>'^\d+$'], name: 'season_show')]
    public function showSeason(int $programId, int $seasonId, ProgramRepository $programRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository)
    {
        $programId = $programRepository->findOneBy(['id' => $programId]);
        if (!$programId) {
            $noProgramFound = 'Aucune série correspondante';
        }

        $seasonId = $seasonRepository->findOneBy(['id' => $seasonId]);
        if (!$seasonId) {
            $noSeasonFound = 'Aucune saison trouvée';
        } else {
            $noSeasonFound = '';
        }

        $episodes = $episodeRepository->findBy(['season_id' => $seasonId]);
        if (!$episodes) {
            $noEpisodeFound = 'Aucun épisode trouvé';
        } else {
            $noEpisodeFound ='';
        }

        return $this->render('program/season_show.html.twig', [
            'program' => $programId,
            'season' => $seasonId,
            'noSeasonFound' => $noSeasonFound,
            'episodes' => $episodes,
            'noEpisodeFound' => $noEpisodeFound,
        ]);
    }
}
