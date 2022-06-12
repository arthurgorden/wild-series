<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Repository\ActorRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/actor', name: 'actor_')]
class ActorController extends AbstractController
{
    private $actorRepository;

    public function __construct(ActorRepository $actorRepository)
    {
        $this->actorRepository = $actorRepository;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('actor/index.html.twig', [
            'actors' => $this->actorRepository->findAll(),
        ]);
    }

    #[Route('/show/{id}', name: 'show', methods: ['GET'], requirements: ['id'=>'^\d+$'])]
    public function show (int $id, ProgramRepository $programRepository, Actor $actorObject): Response
    {
        $actor = $this->actorRepository->findOneBy(['id' => $id]);
        if (!$actor) {
            throw $this->createNotFoundException(
                'No actor with id number ' . $id . ' found in actor\'s table.'
            );
        }
        $programs = $actorObject->getPrograms(['actor' => $actor]);
        if (!$programs) {
            $noProgramFound = 'Aucune série trouvée';
        } else {
            $noProgramFound = '';
        }
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
            'programs' => $programs,
            'noProgramFound' => $noProgramFound,
        ]);
    }
}
