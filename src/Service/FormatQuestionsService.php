<?php

namespace App\Service;

use App\Entity\Pregunta;

class FormatQuestionsService
{
    public function formatQuestions (array $preguntas): array
    {

        foreach ($preguntas as $pregunta) {

            $formatedQuestions[] = $this->createResponseKey($pregunta);
        }
        return $formatedQuestions;
    }

    public function createResponseKey(Pregunta $pregunta): array
    {
        $respuestas = [];
       $respuesta = [
               $pregunta->getRespuestaCorrecta(),
               $pregunta->getRespuestasIncorrectas()[0],
               $pregunta->getRespuestasIncorrectas()[1],
               $pregunta->getRespuestasIncorrectas()[2],
           ];
            $keys = array_keys($respuesta);
             shuffle($keys);

            $arrayDesordenado = [];
            foreach ($keys as $key)
            {
                $arrayDesordenado[$key] = $respuesta[$key];
            }
            $respuesta = [
                'correcta' => $pregunta->getRespuestaCorrecta(),
                'respuestas' => $respuesta,
            ];
            $respuestas[] = $respuesta;

        return $respuestas;
    }

}