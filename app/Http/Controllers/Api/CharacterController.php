<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Character;
use Illuminate\Support\Facades\Http;

class CharacterController extends Controller
{
    /**
     * Listar todos los personajes almacenados
     */
    public function index()
    {
        return Character::all();
    }

    /**
     * Importar personajes desde The Simpsons API (usando CDN)
     */
    public function import()
    {
        $response = Http::get('https://thesimpsonsapi.com/api/characters');
        $data = $response->json();

        // Verificar formato correcto
        if (!isset($data['results']) || !is_array($data['results'])) {
            return response()->json([
                'error' => 'Formato inesperado de la API',
                'response' => $data,
            ], 500);
        }

        $characters = $data['results'];
        $count = 0;

        foreach ($characters as $item) {
            if (!is_array($item) || empty($item['name'] ?? null)) continue;

            // ✅ Usamos la URL CDN con tamaño 500px
            $portraitUrl = isset($item['portrait_path'])
                ? "https://cdn.thesimpsonsapi.com/500{$item['portrait_path']}"
                : null;

            Character::updateOrCreate(
                ['name' => $item['name']],
                [
                    'age' => $item['age'] ?? null,
                    'birthdate' => $item['birthdate'] ?? null,
                    'gender' => $item['gender'] ?? null,
                    'occupation' => $item['occupation'] ?? null,
                    'portrait_path' => $portraitUrl,
                    'phrases' => json_encode($item['phrases'] ?? []),
                    'status' => $item['status'] ?? null,
                ]
            );

            $count++;
        }

        return response()->json([
            'message' => "Se importaron {$count} personajes correctamente usando el CDN",
            'total' => $count,
        ]);
    }

    /**
     * Mostrar un personaje específico
     */
    public function show(Character $character)
    {
        return $character;
    }
}
