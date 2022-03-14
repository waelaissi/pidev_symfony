<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sujet
 *
 * @ORM\Table(name="sujet", indexes={@ORM\Index(name="fk_idtopic", columns={"idtopic"}), @ORM\Index(name="fk_iduser", columns={"iduser"})})
 * @ORM\Entity(repositoryClass="App\Repository\SujetRepository")
 */
class Sujet
{
    /**
     * @var int
     *
     * @ORM\Column(name="idsujet", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idsujet;

    /**
     * @var string
     *
     * @ORM\Column(name="titresujet", type="string", length=255, nullable=false)
     */
    private $titresujet;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text", length=65535, nullable=false)
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="accepter", type="integer", nullable=false)
     */
    private $accepter = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="nbcom", type="integer", nullable=false)
     */
    private $nbcom = '0';

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="id")
     * })
     */
    private $iduser;

    /**
     * @var \Topic
     *
     * @ORM\ManyToOne(targetEntity="Topic")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idtopic", referencedColumnName="idtopic")
     * })
     */
    private $idtopic;

    public function getIdsujet(): ?int
    {
        return $this->idsujet;
    }

    public function getTitresujet(): ?string
    {
        return $this->titresujet;
    }

    public function setTitresujet(string $titresujet): self
    {
        $this->titresujet = $titresujet;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAccepter(): ?int
    {
        return $this->accepter;
    }

    public function setAccepter(int $accepter): self
    {
        $this->accepter = $accepter;

        return $this;
    }

    public function getNbcom(): ?int
    {
        return $this->nbcom;
    }

    public function setNbcom(int $nbcom): self
    {
        $this->nbcom = $nbcom;

        return $this;
    }

    public function getIduser(): ?Utilisateur
    {
        return $this->iduser;
    }

    public function setIduser(?Utilisateur $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIdtopic(): ?Topic
    {
        return $this->idtopic;
    }

    public function setIdtopic(?Topic $idtopic): self
    {
        $this->idtopic = $idtopic;

        return $this;
    }


}
