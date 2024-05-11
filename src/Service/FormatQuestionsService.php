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
       $respuestasorder = [
               $pregunta->getRespuestaCorrecta() ,
               $pregunta->getRespuestasIncorrectas()[0]  ,
               $pregunta->getRespuestasIncorrectas()[1] ,
               $pregunta->getRespuestasIncorrectas()[2] ,
           ];
            $keys = array_keys($respuestasorder);
            shuffle($keys);

            $arrayDesordenado = [];
            foreach ($keys as $key)
            {
                $arrayDesordenado[$key] = $respuestasorder[$key];
            }
            $respuesta = [
                'correcta' => $pregunta->getRespuestaCorrecta(),
                'respuestas' => $arrayDesordenado,
            ];
            $respuestas[] = $respuesta;

        return $respuestas;
    }

}