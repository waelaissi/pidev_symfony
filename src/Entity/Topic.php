<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Topic
 *
 * @ORM\Table(name="topic", indexes={@ORM\Index(name="fk_userid", columns={"iduser"})})
 * @ORM\Entity
 */
class Topic
{
    /**
     * @var int
     *
     * @ORM\Column(name="idtopic", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtopic;

    /**
     * @var string|null
     *
     * @ORM\Column(name="titretopic", type="string", length=255, nullable=true)
     */
    private $titretopic;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="accepter", type="boolean", nullable=false)
     */
    private $accepter = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="nbsujet", type="integer", nullable=false)
     */
    private $nbsujet = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="hide", type="integer", nullable=false)
     */
    private $hide = '0';

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="id")
     * })
     */
    private $iduser;

    public function getIdtopic(): ?int
    {
        return $this->idtopic;
    }

    public function getTitretopic(): ?string
    {
        return $this->titretopic;
    }

    public function setTitretopic(?string $titretopic): self
    {
        $this->titretopic = $titretopic;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAccepter(): ?bool
    {
        return $this->accepter;
    }

    public function setAccepter(bool $accepter): self
    {
        $this->accepter = $accepter;

        return $this;
    }

    public function getNbsujet(): ?int
    {
        return $this->nbsujet;
    }

    public function setNbsujet(int $nbsujet): self
    {
        $this->nbsujet = $nbsujet;

        return $this;
    }

    public function getHide(): ?int
    {
        return $this->hide;
    }

    public function setHide(int $hide): self
    {
        $this->hide = $hide;

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


}
