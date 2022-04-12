<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="id_transaction", columns={"id_transaction"}), @ORM\Index(name="id_user", columns={"id_user"}), @ORM\Index(name="fk_resvoiture", columns={"id_voiture"}), @ORM\Index(name="id_maison", columns={"id_maison"}), @ORM\Index(name="fkchambre", columns={"id_chambre"}), @ORM\Index(name="id_ticket", columns={"id_ticket"})})
 * @ORM\Entity
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
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255, nullable=false, options={"default"="confirmé"})
     */
    private $etat = 'confirmée';

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20, nullable=false)
     */
    private $type;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;

    /**
     * @var \Voiture
     *
     * @ORM\ManyToOne(targetEntity="Voiture")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_voiture", referencedColumnName="id")
     * })
     */
    private $idVoiture;

    /**
     * @var \Maison
     *
     * @ORM\ManyToOne(targetEntity="Maison")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_maison", referencedColumnName="id")
     * })
     */
    private $idMaison;

    /**
     * @var \Transaction
     *
     * @ORM\ManyToOne(targetEntity="Transaction")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_transaction", referencedColumnName="id")
     * })
     */
    private $idTransaction;

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
     * @var \Ticket
     *
     * @ORM\ManyToOne(targetEntity="Ticket")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_ticket", referencedColumnName="id")
     * })
     */
    private $idTicket;

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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

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

    public function getIdUser(): ?Utilisateur
    {
        return $this->idUser;
    }

    public function setIdUser(?Utilisateur $idUser): self
    {
        $this->idUser = $idUser;

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

    public function getIdMaison(): ?Maison
    {
        return $this->idMaison;
    }

    public function setIdMaison(?Maison $idMaison): self
    {
        $this->idMaison = $idMaison;

        return $this;
    }

    public function getIdTransaction(): ?Transaction
    {
        return $this->idTransaction;
    }

    public function setIdTransaction(?Transaction $idTransaction): self
    {
        $this->idTransaction = $idTransaction;

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

    public function getIdTicket(): ?Ticket
    {
        return $this->idTicket;
    }

    public function setIdTicket(?Ticket $idTicket): self
    {
        $this->idTicket = $idTicket;

        return $this;
    }


}
