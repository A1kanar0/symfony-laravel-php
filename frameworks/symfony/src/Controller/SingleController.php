<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/singles', name: 'api_singles_')]
class SingleController extends AbstractController
{
    private array $singles = [
        1 => ['id' => 1, 'title' => 'Blinding Lights', 'artist' => 'The Weeknd', 'duration' => '3:20', 'genre' => 'Synth-pop'],
        2 => ['id' => 2, 'title' => 'Shape of You', 'artist' => 'Ed Sheeran', 'duration' => '3:53', 'genre' => 'Pop'],
        3 => ['id' => 3, 'title' => 'Stay', 'artist' => 'The Kid LAROI & Justin Bieber', 'duration' => '2:21', 'genre' => 'Pop rock'],
    ];

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json(array_values($this->singles));
    }

    #[Route('', name: 'store', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        $content = json_decode($request->getContent(), true) ?? [];

        $newId = max(array_keys($this->singles)) + 1;

        $newSingle = [
            'id' => $newId,
            'title' => $content['title'] ?? 'Unknown Track',
            'artist' => $content['artist'] ?? 'Unknown Artist',
            'duration' => $content['duration'] ?? '0:00',
            'genre' => $content['genre'] ?? 'Unknown Genre',
        ];

        $this->singles[$newId] = $newSingle;

        return $this->json([
            'message' => 'Сингл успішно додано',
            'data' => $newSingle
        ], 201);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        if (!isset($this->singles[$id])) {
            return $this->json(['error' => 'Сингл не знайдено'], 404);
        }

        return $this->json($this->singles[$id]);
    }

    #[Route('/{id}', name: 'update', methods: ['PATCH'])]
    public function update(Request $request, int $id): JsonResponse
    {
        if (!isset($this->singles[$id])) {
            return $this->json(['error' => 'Сингл не знайдено'], 404);
        }

        $content = json_decode($request->getContent(), true) ?? [];
        $single = $this->singles[$id];

        if (isset($content['title'])) $single['title'] = $content['title'];
        if (isset($content['artist'])) $single['artist'] = $content['artist'];
        if (isset($content['duration'])) $single['duration'] = $content['duration'];
        if (isset($content['genre'])) $single['genre'] = $content['genre'];

        $this->singles[$id] = $single;

        return $this->json([
            'message' => 'Сингл оновлено',
            'data' => $single
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function destroy(int $id): JsonResponse
    {
        if (!isset($this->singles[$id])) {
            return $this->json(['error' => 'Сингл не знайдено'], 404);
        }

        $deletedSingle = $this->singles[$id];
        unset($this->singles[$id]);

        return $this->json([
            'message' => 'Сингл видалено з бази',
            'deleted_data' => $deletedSingle
        ]);
    }
}
