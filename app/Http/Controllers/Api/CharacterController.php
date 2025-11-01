<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Character;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
     * Importar personajes desde The Simpsons API y guardar imágenes localmente
     */
    public function import()
    {
        $response = Http::get('https://thesimpsonsapi.com/api/characters');
        $data = $response->json();

        // Verificar estructura
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

            // URL completa de la imagen
            $imageUrl = isset($item['portrait_path'])
                ? 'https://thesimpsonsapi.com' . $item['portrait_path']
                : null;

            $localImagePath = null;

            // Descargar imagen y guardarla en storage/app/public/characters
            if ($imageUrl) {
                try {
                    $imageContents = Http::get($imageUrl)->body();

                    // Nombre del archivo, seguro para el sistema de archivos
                    $fileName = str_replace(' ', '_', strtolower($item['name'])) . '.webp';

                    // Guardar la imagen
                    Storage::disk('public')->put('characters/' . $fileName, $imageContents);

                    // Ruta pública
                    $localImagePath = 'storage/characters/' . $fileName;
                } catch (\Exception $e) {
                    // Si falla la descarga, seguimos sin interrumpir el proceso
                    $localImagePath = null;
                }
            }

            // Guardar personaje en la base de datos
            Character::updateOrCreate(
                ['name' => $item['name']],
                [
                    'age' => $item['age'] ?? null,
                    'birthdate' => $item['birthdate'] ?? null,
                    'gender' => $item['gender'] ?? null,
                    'occupation' => $item['occupation'] ?? null,
                    'portrait_path' => $localImagePath, // Guardamos la ruta local
                    'phrases' => json_encode($item['phrases'] ?? []),
                    'status' => $item['status'] ?? null,
                ]
            );

            $count++;
        }

        return response()->json([
            'message' => "Se importaron {$count} personajes correctamente",
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
