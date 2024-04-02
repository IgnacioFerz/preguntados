<?php

namespace App\Entity;

use App\Repository\PartidaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartidaRepository::class)]
class Partida
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $jugador1 = null;

    #[ORM\Column]
    private ?int $jugador2 = null;

    #[ORM\Column]
    private ?int $puntuajeJugador1 = null;

    #[ORM\Column]
    private ?int $puntuajeJugador2 = null;

    #[ORM\Column(length: 255)]
    private ?string $estado = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJugador1(): ?int
    {
        return $this->jugador1;
    }

    public function setJugador1(int $jugador1): static
    {
        $this->jugador1 = $jugador1;

        return $this;
    }

    public function getJugador2(): ?int
    {
        return $this->jugador2;
    }

    public function setJugador2(int $jugador2): static
    {
        $this->jugador2 = $jugador2;

        return $this;
    }

    public function getPuntuajeJugador1(): ?int
    {
        return $this->puntuajeJugador1;
    }

    public function setPuntuajeJugador1(int $puntuajeJugador1): static
    {
        $this->puntuajeJugador1 = $puntuajeJugador1;

        return $this;
    }

    public function getPuntuajeJugador2(): ?int
    {
        return $this->puntuajeJugador2;
    }

    public function setPuntuajeJugador2(int $puntuajeJugador2): static
    {
        $this->puntuajeJugador2 = $puntuajeJugador2;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): static
    {
        $this->estado = $estado;

        return $this;
    }
}
