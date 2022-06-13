<?php

// src/Controller/ProgramController.php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Slugify;

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

    #[Route('/new', 'new')]
    public function new(Request $request, ProgramRepository $programRepository, Slugify $slugify): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $programRepository->add($program, true);
            return $this->redirectToRoute('program_index');
        }

        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/show/{slug}', methods: ['GET'], requirements: ['slug'=>'^[a-z0-9]+(?:-[a-z0-9]+)*$'], name: 'show')]
    public function show(string $slug, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    {
        $program = $programRepository->findOneBy(['slug' => $slug]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with title : '.$slug.' found in program\'s table.'
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

    #[Route('/{programSlug}/season/{seasonId}', methods: ['GET'], requirements: ['programSlug'=>'^[a-z0-9]+(?:-[a-z0-9]+)*$', 'seasonId' =>'^\d+$'], name: 'season_show')]
    public function showSeason(string $programSlug, int $seasonId, ProgramRepository $programRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository)
    {
        $programSlug = $programRepository->findOneBy(['slug' => $programSlug]);
        if (!$programSlug) {
            $noProgramFound = 'Aucune série correspondante';
        } else {
            $noProgramFound = '';
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
            'program' => $programSlug,
            'noProgramFound' => $noProgramFound,
            'season' => $seasonId,
            'noSeasonFound' => $noSeasonFound,
            'episodes' => $episodes,
            'noEpisodeFound' => $noEpisodeFound,
        ]);
    }

    #[Route('/{programSlug}/season/{seasonId}/episode/{episodeSlug}', methods: ['GET'], requirements: ['programSlug'=>'^[a-z0-9]+(?:-[a-z0-9]+)*$', 'seasonId' =>'^\d+$', 'episodeSlug'=>'^[a-z0-9]+(?:-[a-z0-9]+)*$'], name: 'episode_show')]
    // Ne pas oublier le use Sensio\...\Entity
    #[Entity('program', options: ['mapping' => ['programSlug' => 'slug']])]
    #[Entity('season', options: ['id' => 'seasonId'])]
    #[Entity('episode', options: ['mapping' => ['episodeSlug' => 'slug']])]
    public function showEpisode(Program $program, Season $season, Episode $episode) : Response
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}
