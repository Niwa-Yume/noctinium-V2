<?php

namespace App\Controller;

use App\Repository\InteretRepository;
use App\Repository\StatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profil', name: 'app_profile')]
final class ProfileController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('', name: '_index', methods: ['GET'])]
    public function index(
        InteretRepository $interetRepo,
        StatusRepository $statusRepo
    ): Response {
        $user   = $this->getUser();
        $status = $statusRepo->findOneBy(['status' => "j'y vais"]);
        $interets = $status
            ? $interetRepo->findBy(['user' => $user, 'status' => $status])
            : [];

        $events = array_map(fn($i) => $i->getEvent(), $interets);

        return $this->render('profile/index.html.twig', [
            'events' => $events,
        ]);
    }
}
