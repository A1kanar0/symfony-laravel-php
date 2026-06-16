<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/albums', name: 'api_albums_')]
class AlbumController extends AbstractController
{
    private array $albums = [
        1 => ['id' => 1, 'title' => 'Dark Side of the Moon', 'artist' => 'Pink Floyd', 'year' => 1973],
        2 => ['id' => 2, 'title' => 'Abbey Road', 'artist' => 'The Beatles', 'year' => 1969],
        3 => ['id' => 3, 'title' => 'Thriller', 'artist' => 'Michael Jackson', 'year' => 1982],
    ];

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json(array_values($this->albums));
    }

    #[Route('', name: 'store', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        $content = json_decode($request->getContent(), true) ?? [];

        $newId = max(array_keys($this->albums)) + 1;

        $newAlbum = [
            'id' => $newId,
            'title' => $content['title'] ?? 'Unknown Title',
            'artist' => $content['artist'] ?? 'Unknown Artist',
            'year' => $content['year'] ?? 2024,
        ];

        $this->albums[$newId] = $newAlbum;

        return $this->json([
            'message' => 'Альбом успішно створено',
            'data' => $newAlbum
        ], 201);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        if (!isset($this->albums[$id])) {
            return $this->json(['error' => 'Альбом не знайдено'], 404);
        }

        return $this->json($this->albums[$id]);
    }

    #[Route('/{id}', name: 'update', methods: ['PATCH'])]
    public function update(Request $request, int $id): JsonResponse
    {
        if (!isset($this->albums[$id])) {
            return $this->json(['error' => 'Альбом не знайдено'], 404);
        }

        $content = json_decode($request->getContent(), true) ?? [];
        $album = $this->albums[$id];

        // Імітуємо оновлення
        if (isset($content['title'])) $album['title'] = $content['title'];
        if (isset($content['artist'])) $album['artist'] = $content['artist'];
        if (isset($content['year'])) $album['year'] = $content['year'];

        $this->albums[$id] = $album;

        return $this->json([
            'message' => 'Альбом оновлено',
            'data' => $album
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function destroy(int $id): JsonResponse
    {
        if (!isset($this->albums[$id])) {
            return $this->json(['error' => 'Альбом не знайдено'], 404);
        }

        $deletedAlbum = $this->albums[$id];
        unset($this->albums[$id]);

        return $this->json([
            'message' => 'Альбом видалено',
            'deleted_data' => $deletedAlbum
        ]);
    }
}
