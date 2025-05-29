<?php

namespace App\Service;

use App\Repository\EventRepository;

class EventStatsService
{
    private EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function countAll(): int
    {
        return $this->eventRepository->count([]);
    }

    public function countByCategory(): array
    {
        $qb = $this->eventRepository->createQueryBuilder('e')
            ->select('c.name AS name, COUNT(e.id) AS nb')
            ->join('e.categories', 'c')
            ->groupBy('c.name');
        return $qb->getQuery()->getResult();
    }

    public function countUpcoming(): int
    {
        return $this->eventRepository->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->where('e.date > :now')
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getSingleScalarResult();
    }
}