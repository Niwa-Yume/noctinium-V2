<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
//ici on peut mettre un route pour définir les routes d'en dessous #[Route('/event', name: 'app_event_nom_de_la_route')]
#[Route('/event', name: 'app_event_')]
final class EventController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }


    //dans l'url ce sera event/addEvent
    #[Route('/addEvent', name: 'add_event')]
    public function addEvent(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'ADD Event',
        ]);
    }
}
