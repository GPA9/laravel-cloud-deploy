<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Character;
use Illuminate\Support\Facades\Http;

class CharacterController extends Controller
{
    // Listar todos los personajes
    public function index()
    {
        return Character::all();
    }

    // Importar personajes desde The Simpsons API
    public function import()
    {
        $response = Http::get('https://thesimpsonsapi.com/api/characters'); // ajusta si hay paginación
        $data = $response->json();

        foreach ($data as $item) {
            Character::updateOrCreate(
                ['id' => $item['id']],
                [
                    'name' => $item['name'],
                    'age' => $item['age'],
                    'birthdate' => $item['birthdate'],
                    'gender' => $item['gender'],
                    'occupation' => $item['occupation'],
                    'portrait_path' => $item['portrait_path'],
                    'phrases' => $item['phrases'],
                    'status' => $item['status'],
                ]
            );
        }

        return response()->json(['message' => 'Personajes importados correctamente']);
    }

    // Mostrar un personaje
    public function show(Character $character)
    {
        return $character;
    }
}
