<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="fk_resvoiture", columns={"id_voiture"}), @ORM\Index(name="id_maison", columns={"id_maison"}), @ORM\Index(name="fkchambre", columns={"id_chambre"}), @ORM\Index(name="id_ticket", columns={"id_ticket"}), @ORM\Index(name="id_transaction", columns={"id_transaction"}), @ORM\Index(name="id_user", columns={"id_user"})})
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_debut", type="date", nullable=true)
     */
    private $dateDebut;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_fin", type="date", nullable=true)
     */
    private $dateFin;

    /**
     * @var float
     *
     * @ORM\Column(name="montant_a_payer", type="float", precision=10, scale=0, nullable=false)
     */
    private $montantAPayer;

    /**
     * @var float|null
     *
     * @ORM\Column(name="reste_a_payer", type="float", precision=10, scale=0, nullable=true)
     */
    private $resteAPayer;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_ticket", type="integer", nullable=true)
     */
    private $idTicket;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_maison", type="integer", nullable=true)
     */
    private $idMaison;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_user", type="integer", nullable=true)
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255, nullable=false, options={"default"="confirmé"})
     */
    private $etat = 'confirmé';

    /**
     * @var int
     *
     * @ORM\Column(name="id_transaction", type="integer", nullable=false)
     */
    private $idTransaction;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20, nullable=false)
     */
    private $type;

    /**
     * @var \Chambre
     *
     * @ORM\ManyToOne(targetEntity="Chambre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_chambre", referencedColumnName="id")
     * })
     */
    private $idChambre;

    /**
     * @var \Voiture
     *
     * @ORM\ManyToOne(targetEntity="Voiture")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_voiture", referencedColumnName="id")
     * })
     */
    private $idVoiture;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getMontantAPayer(): ?float
    {
        return $this->montantAPayer;
    }

    public function setMontantAPayer(float $montantAPayer): self
    {
        $this->montantAPayer = $montantAPayer;

        return $this;
    }

    public function getResteAPayer(): ?float
    {
        return $this->resteAPayer;
    }

    public function setResteAPayer(?float $resteAPayer): self
    {
        $this->resteAPayer = $resteAPayer;

        return $this;
    }

    public function getIdTicket(): ?int
    {
        return $this->idTicket;
    }

    public function setIdTicket(?int $idTicket): self
    {
        $this->idTicket = $idTicket;

        return $this;
    }

    public function getIdMaison(): ?int
    {
        return $this->idMaison;
    }

    public function setIdMaison(?int $idMaison): self
    {
        $this->idMaison = $idMaison;

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(?int $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getIdTransaction(): ?int
    {
        return $this->idTransaction;
    }

    public function setIdTransaction(int $idTransaction): self
    {
        $this->idTransaction = $idTransaction;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getIdChambre(): ?Chambre
    {
        return $this->idChambre;
    }

    public function setIdChambre(?Chambre $idChambre): self
    {
        $this->idChambre = $idChambre;

        return $this;
    }

    public function getIdVoiture(): ?Voiture
    {
        return $this->idVoiture;
    }

    public function setIdVoiture(?Voiture $idVoiture): self
    {
        $this->idVoiture = $idVoiture;

        return $this;
    }


}
