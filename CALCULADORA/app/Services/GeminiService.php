<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    private string $url;

    public function __construct()
    {
        $apiKey    = config('services.gemini.key');
        $this->url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-lite-latest:generateContent?key={$apiKey}";
    }

    /**
     * Genera una palabra y su pista para el ahorcado según un tema.
     * Devuelve ['palabra' => '...', 'pista' => '...'] o null si falla.
     */
    public function generateWord(string $tema): ?array
    {
        $prompt = "Actúa como un backend para un juego del ahorcado. "
                . "El usuario quiere el tema: '{$tema}'. "
                . "Genera una palabra en español relacionada con ese tema y una pista breve. "
                . "La palabra no debe tener tildes ni caracteres especiales. "
                . "Responde ÚNICAMENTE con un JSON válido con este formato exacto, "
                . "sin texto adicional, sin bloques de código, sin comillas adicionales: "
                . "{\"palabra\": \"EJEMPLO\", \"pista\": \"Texto de la pista\"}";

        try {
            $response = Http::timeout(20)->post($this->url, [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
            ]);

            if ($response->failed()) {
                return null;
            }

            $data      = $response->json();
            $jsonString = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

            // Limpiar posibles bloques de código que Gemini a veces añade
            $jsonString = preg_replace('/```json|```/i', '', $jsonString);
            $jsonString = trim($jsonString);

            $resultado = json_decode($jsonString, true);

            // Validar que tenga las claves esperadas
            if (
                is_array($resultado) &&
                isset($resultado['palabra'], $resultado['pista']) &&
                strlen($resultado['palabra']) > 0
            ) {
                return [
                    'palabra' => strtolower(trim($resultado['palabra'])),
                    'pista'   => trim($resultado['pista']),
                ];
            }

            return null;

        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Consultorio: devuelve dos consejos sobre un tema,
     * uno actual y otro con contexto de los años 70 en España.
     */
    public function consultar(string $tema): array
    {
        $promptActual  = "{$tema}. Aconséjame teniendo en cuenta la situación actual. "
                       . "Responde de forma clara y directa, en español.";

        $promptVintage = "{$tema}. Aconséjame teniendo en cuenta la situación social, "
                       . "tecnológica y cultural de los años 70 en España. "
                       . "Responde de forma clara y directa, en español, "
                       . "como si estuvieras viviendo en esa época.";

        return [
            'actual'  => $this->llamarApi($promptActual),
            'vintage' => $this->llamarApi($promptVintage),
        ];
    }

    // ── Privado ──────────────────────────────────────────────

    private function llamarApi(string $prompt): string
    {
        try {
            $response = Http::timeout(20)->post($this->url, [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
            ]);

            if ($response->failed()) {
                return 'Error al contactar con el servicio (' . $response->status() . '). Inténtalo de nuevo.';
            }

            $data = $response->json();

            return $data['candidates'][0]['content']['parts'][0]['text']
                ?? 'No se pudo obtener una respuesta. Inténtalo de nuevo.';

        } catch (\Exception $e) {
            return 'Error de conexión: ' . $e->getMessage();
        }
    }
}