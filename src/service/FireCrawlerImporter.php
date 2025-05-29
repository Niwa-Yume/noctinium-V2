<?php

namespace App\service;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryRepository;
use App\Repository\EventRepository;

class FireCrawlerImporter
{
    private $em;
    private $categoryRepository;
    private $eventRepository;

    public function __construct(
        EntityManagerInterface $em,
        CategoryRepository $categoryRepository,
        EventRepository $eventRepository
    ) {
        $this->em = $em;
        $this->categoryRepository = $categoryRepository;
        $this->eventRepository = $eventRepository;
    }

    public function importFromDecadanse(): int
    {
        // Clé API Firecrawl stockée dans .env.local
        $apiKey = $_ENV['FIRECRAWL_API_KEY'] ?? getenv('FIRECRAWL_API_KEY');
        if (!$apiKey) {
            throw new \RuntimeException("Clé API Firecrawl manquante dans .env.local");
        }

        $urlToCrawl = 'https://www.ladecadanse.ch/evenement-agenda.php?';

        $payload = json_encode([
            'url' => $urlToCrawl,
            // 'maxDepth' => 2, // optionnel
            // 'scrapeOptions' => ['formats' => ['markdown']], // optionnel, adapte si besoin
        ]);

        $ch = curl_init('https://api.firecrawl.dev/v1/crawl');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true); // Requête POST
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            throw new \RuntimeException("Erreur lors de la requête Firecrawl: HTTP $httpCode — $err — $response");
        }

        $data = json_decode($response, true);
        if (!$data) {
            throw new \RuntimeException("Données JSON invalides reçues de Firecrawl.");
        }

        //var_dump($data);
        //exit;

        $response = '{"test": "ça marche"}';
        $data = json_decode($response, true);

        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
        exit;


        // À adapter selon le format exact de la réponse Firecrawl
        if (isset($data['events'])) {
            $events = $data['events'];
        } elseif (isset($data['scrapedData']['markdown'])) {
            $events = $this->parseEventsFromMarkdown($data['scrapedData']['markdown']);
        } elseif (isset($data['scrapedData']['text'])) {
            $events = $this->parseEventsFromMarkdown($data['scrapedData']['text']);
        } else {
            throw new \RuntimeException("Aucun événement trouvé dans la réponse Firecrawl.");
        }

        // Préparation des catégories
        $categoriesWanted = [
            'rap' => null,
            'latino' => null,
            'techno' => null,
        ];
        foreach ($categoriesWanted as $cat => $v) {
            $categoriesWanted[$cat] = $this->categoryRepository->findOneBy(['name' => ucfirst($cat)]);
        }
        $defaultCategory = $this->categoryRepository->findOneBy(['name' => 'Autres']);

        $count = 0;
        foreach ($events as $eventData) {
            // À adapter selon la forme réelle des données d'event
            $title = $eventData['title'] ?? null;
            $date = $eventData['date'] ?? null;
            $categoryLabel = $eventData['category'] ?? '';
            $location = $eventData['location'] ?? null;

            if (!$title || !$date) continue;

            $catKey = strtolower(trim($categoryLabel));
            $cat = $categoriesWanted[$catKey] ?? $defaultCategory;

            // Tentative de conversion de la date
            $dateObj = \DateTime::createFromFormat('Y-m-d\TH:i:sP', $date);
            if (!$dateObj) {
                $dateObj = new \DateTime($date); // fallback large
            }

            $existing = $this->eventRepository->findOneBy(['title' => $title, 'date' => $dateObj]);
            if ($existing) {
                continue;
            }

            $event = new Event();
            $event->setTitle($title);
            $event->setDate($dateObj);
            $event->setLocation($location);
            $event->setCategory($cat);

            $this->em->persist($event);
            $count++;
        }
        $this->em->flush();

        return $count;
    }

    /**
     * À adapter : Parse le markdown retourné par Firecrawl et extrait les événements sous forme de tableau
     * @param string $markdown
     * @return array
     */
    private function parseEventsFromMarkdown(string $markdown): array
    {
        // À personnaliser selon le format du markdown reçu de Firecrawl !
        // Tu peux m’envoyer un exemple et je te fais le parseur exact.
        return [];
    }
}
