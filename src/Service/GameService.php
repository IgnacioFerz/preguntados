<?php

namespace App\Service;

class GameService
{
    public function getQuestions()
    {
        $url = "https://opentdb.com/api.php?amount=10";
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        return $data['results'];
    }

}