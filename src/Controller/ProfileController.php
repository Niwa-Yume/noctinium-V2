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
    public function index(InteretRepository $interetRepo): Response
    {
        $user     = $this->getUser();
        $interets = $interetRepo->findBy(['user' => $user]);

        $going    = [];
        $notGoing = [];
        foreach ($interets as $i) {
            $s = $i->getStatus()?->getStatus();
            if ($s === "j'y vais") {
                $going[] = $i->getEvent();
            } elseif ($s === "je n'y vais pas") {
                $notGoing[] = $i->getEvent();
            }
        }

        return $this->render('profile/index.html.twig', [
            'going'    => $going,
            'notGoing' => $notGoing,
        ]);
    }
}
