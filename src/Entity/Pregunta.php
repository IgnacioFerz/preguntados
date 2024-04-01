<?php

namespace App\Entity;

use App\Repository\PreguntaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PreguntaRepository::class)]
class Pregunta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $pregunta = null;

    #[ORM\Column(length: 255)]
    private ?string $respuestaCorrecta = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tema = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPregunta(): ?string
    {
        return $this->pregunta;
    }

    public function setPregunta(string $pregunta): static
    {
        $this->pregunta = $pregunta;

        return $this;
    }

    public function getRespuestaCorrecta(): ?string
    {
        return $this->respuestaCorrecta;
    }

    public function setRespuestaCorrecta(string $respuestaCorrecta): static
    {
        $this->respuestaCorrecta = $respuestaCorrecta;

        return $this;
    }

    public function getTema(): ?string
    {
        return $this->tema;
    }

    public function setTema(?string $tema): static
    {
        $this->tema = $tema;

        return $this;
    }
}
