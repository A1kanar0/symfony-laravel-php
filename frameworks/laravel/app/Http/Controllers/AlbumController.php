<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlbumController extends Controller
{
    private array $albums = [
        1 => ['id' => 1, 'title' => 'Dark Side of the Moon', 'artist' => 'Pink Floyd', 'year' => 1973],
        2 => ['id' => 2, 'title' => 'Abbey Road', 'artist' => 'The Beatles', 'year' => 1969],
        3 => ['id' => 3, 'title' => 'Thriller', 'artist' => 'Michael Jackson', 'year' => 1982],
    ];

    // GET /api/albums
    public function index()
    {
        return response()->json(array_values($this->albums));
    }

    // POST /api/albums
    public function store(Request $request)
    {
        $newId = max(array_keys($this->albums)) + 1;

        $newAlbum = [
            'id' => $newId,
            'title' => $request->input('title', 'Unknown Title'),
            'artist' => $request->input('artist', 'Unknown Artist'),
            'year' => $request->input('year', 2024),
        ];

        $this->albums[$newId] = $newAlbum;

        return response()->json([
            'message' => 'Альбом успішно створено',
            'data' => $newAlbum
        ], 201);
    }

    // GET /api/albums/{id}
    public function show($id)
    {
        if (!isset($this->albums[$id])) {
            return response()->json(['error' => 'Альбом не знайдено'], 404);
        }

        return response()->json($this->albums[$id]);
    }

    // PATCH /api/albums/{id}
    public function update(Request $request, $id)
    {
        if (!isset($this->albums[$id])) {
            return response()->json(['error' => 'Альбом не знайдено'], 404);
        }

        $album = $this->albums[$id];
        $album['title'] = $request->input('title', $album['title']);
        $album['artist'] = $request->input('artist', $album['artist']);
        $album['year'] = $request->input('year', $album['year']);

        $this->albums[$id] = $album;

        return response()->json([
            'message' => 'Альбом оновлено',
            'data' => $album
        ]);
    }

    // DELETE /api/albums/{id}
    public function destroy($id)
    {
        if (!isset($this->albums[$id])) {
            return response()->json(['error' => 'Альбом не знайдено'], 404);
        }

        $deletedAlbum = $this->albums[$id];
        unset($this->albums[$id]);

        return response()->json([
            'message' => 'Альбом видалено',
            'deleted_data' => $deletedAlbum
        ]);
    }
}
