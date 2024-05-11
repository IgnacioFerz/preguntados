<?php

namespace App\Service;

use Exception;

class GetQuestionsFromApi
{
    public function getPreguntasFromTrivialAPI(): array
    {
        $apiUrl = 'https://the-trivia-api.com/api/questions?limit=10'; // URL de la API con 10 preguntas tipo multiple
        $response = file_get_contents($apiUrl); // Obtener la respuesta JSON de la API
        $responseData = json_decode($response, true); // Decodificar JSON a un array

        if ($responseData)
        { // Check if any data is returned
            $preguntas = []; // Array para almacenar las preguntas

            foreach ($responseData as $preguntaData)
            {
                $respuestasorder =
                    [
                        $preguntaData['correctAnswer'] => true,
                        $preguntaData['incorrectAnswers'][0] => false,
                        $preguntaData['incorrectAnswers'][1] => false,
                        $preguntaData['incorrectAnswers'][2] => false,
                    ];
                $keys = array_keys($respuestasorder);
                shuffle($keys);
                $arrayDesordenado = [];
                foreach ($keys as $key)
                {
                    $arrayDesordenado[$key] = $respuestasorder[$key];
                }
                $pregunta = [
                    'texto' => $preguntaData['question'],
                    'respuestas' => $arrayDesordenado
                ];
                $preguntas[] = $pregunta;
            }
            return $preguntas;

        } else
        {
            // Manejar error al obtener preguntas de la API
            throw new Exception('Error al obtener preguntas de Trivial API');
        }

    }

    public function getCorrectAnswer($answersArray): ?string
    {
        foreach ($answersArray as $answer => $isCorrect) {
            if ($isCorrect === true) {
                return $answer;
            }
        }
        return null;
    }

    public function getIncorrectAnswer($answersArray): ?array
    {
        $respuestasIncorrectas = [];

        foreach ($answersArray as $respuesta => $esCorrecto) {
            if ($esCorrecto === false) {
                $respuestasIncorrectas[] = $respuesta;
            }
        }

        if (empty($respuestasIncorrectas)) {
            return null;
        } else {
            return $respuestasIncorrectas;
        }
    }
}