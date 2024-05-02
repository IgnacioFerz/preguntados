<?php

namespace App\Entity;

use App\Repository\PartidaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartidaRepository::class)]
class Partida
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'partidas')]
    private ?User $jugador1 = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'partidas')]
    private ?User $jugador2 = null;

    #[ORM\Column (nullable: true)]
    private ?int $puntuajeJugador1 = null;

    #[ORM\Column (nullable: true)]
    private ?int $puntuajeJugador2 = null;

    #[ORM\Column(length: 255)]
    private ?string $estado = null;

    #[ORM\OneToMany(targetEntity: Pregunta::class, mappedBy: 'partida')]
    private Collection $preguntas;

    #[ORM\Column(nullable: true)]
    private ?int $preguntaJugador1 = null;

    #[ORM\Column(nullable: true)]
    private ?int $preguntaJugador2 = null;

    public function __construct()
    {
        $this->preguntas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJugador1(): ?User
    {
        return $this->jugador1;
    }

    public function setJugador1(?User $jugador1): self
    {
        $this->jugador1 = $jugador1;

        return $this;
    }

    public function getJugador2(): ?User
    {
        return $this->jugador2;
    }

    public function setJugador2(?User $jugador2): self
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

    /**
     * @return Collection<int, Pregunta>
     */
    public function getPreguntas(): Collection
    {
        return $this->preguntas;
    }

    public function addPregunta(Pregunta $pregunta): static
    {
        if (!$this->preguntas->contains($pregunta)) {
            $this->preguntas->add($pregunta);
            $pregunta->setPartida($this);
        }

        return $this;
    }

    public function removePregunta(Pregunta $pregunta): static
    {
        if ($this->preguntas->removeElement($pregunta)) {
            // set the owning side to null (unless already changed)
            if ($pregunta->getPartida() === $this) {
                $pregunta->setPartida(null);
            }
        }

        return $this;
    }

    public function getPreguntaJugador1(): ?int
    {
        return $this->preguntaJugador1;
    }

    public function setPreguntaJugador1(?int $preguntaJugador1): static
    {
        $this->preguntaJugador1 = $preguntaJugador1;

        return $this;
    }

    public function getPreguntaJugador2(): ?int
    {
        return $this->preguntaJugador2;
    }

    public function setPreguntaJugador2(?int $preguntaJugador2): static
    {
        $this->preguntaJugador2 = $preguntaJugador2;

        return $this;
    }
}
