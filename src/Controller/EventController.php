<?php

namespace App\Controller;

use App\Entity\Interet;
use App\Entity\Status;
use App\Form\NewEventForm;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Event;
use App\controller;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\CategoryRepository;



#[Route('/event', name: 'app_event_')]
final class EventController extends AbstractController
{

    #[Route('', name: 'index')]
    public function index(
        EventRepository $eventRepository,
        CategoryRepository $categoryRepository,
        Request $request
    ): Response {
        $categoryId = $request->query->get('category');
        $categories = $categoryRepository->findAll();

        if ($categoryId) {
            $events = $eventRepository->createQueryBuilder('e')
                ->join('e.categories', 'c')
                ->where('c.id = :cat')
                ->setParameter('cat', $categoryId)
                ->getQuery()
                ->getResult();
        } else {
            $events = $eventRepository->findAll();
        }

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
        ]);
    }

    // LE SHOW
    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(
        int $id,
        EventRepository $eventRepository,
        EntityManagerInterface $em
    ): Response {
        $event = $eventRepository->find($id);
        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé.');
        }

        // récupère tous les status
        $statuses = $em->getRepository(Status::class)->findAll();

        // récupère l'intérêt de l'utilisateur s'il est connecté
        $interet = null;
        if ($this->getUser()) {
            $interet = $em
                ->getRepository(Interet::class)
                ->findOneBy([
                    'event' => $event,
                    'user'  => $this->getUser(),
                ]);
        }

        return $this->render('event/show.html.twig', [
            'event'    => $event,
            'statuses' => $statuses,
            'interet'  => $interet,
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {

        $event = new Event();
        $form = $this->createForm(NewEventForm::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de l'utilisateur connecté
            $event->setCreatedBy($this->getUser());
            // persiste les données
            $entityManager->persist($event);
            // enregistre les données
            $entityManager->flush();

            // Redirection après la soumission réussie (OBLIGATOIR sinon erreur chelou)
            return $this->redirectToRoute('app_event_index');
        }

        return $this->render('event/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, EventRepository $eventRepository, EntityManagerInterface $entityManager): Response
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé.');
        }

        $form = $this->createForm(NewEventForm::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index');
        }

        return $this->render('event/edit.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(int $id, EventRepository $eventRepository, EntityManagerInterface $entityManager): Response
    {
        $event = $eventRepository->find($id);

        if ($event) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index');
    }

    //ROUTE POUR SIGNALER UN INTERET
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/{id}/interet', name: 'interet', methods: ['POST'])]
    public function interet(Request $request, Event $event, EntityManagerInterface $em): Response
    {
        $user      = $this->getUser();
        $statusId  = $request->request->get('status');
        $status    = $em->getRepository(Status::class)->find($statusId);
        $repo      = $em->getRepository(Interet::class);
        $interet   = $repo->findOneBy(['event'=>$event, 'user'=>$user]) ?? new Interet();

        $interet
            ->setEvent($event)
            ->setUser($user)
            ->setStatus($status);
        if (null === $interet->getId()) {
            $interet->setCreatedAt(new \DateTimeImmutable());
            $em->persist($interet);
        }
        $em->flush();
        dump($interet);

        return $this->redirectToRoute('app_profile_index');
    }
}
