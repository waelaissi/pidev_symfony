<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vangrg\ProfanityBundle\Validator\Constraints as ProfanityAssert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire", indexes={@ORM\Index(name="fk_idusercom", columns={"iduser"}), @ORM\Index(name="fk_idsujet", columns={"idsujet"})})
 * @ORM\Entity
 */
class Commentaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="idcom", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("commentaires")
     */
    private $idcom;

    /**
     * @var string
     * @Assert\NotBlank
     *  @ProfanityAssert\ProfanityCheck
     * @Assert\Length(
     *      min = 15,
     *      max = 255,
     *      minMessage = "Le commentaire doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Le commentaire ne peut pas dépasser {{ limit }} caractères"
     * )
     * @ORM\Column(name="contenu", type="text", length=65535, nullable=false)
     *  @Groups("commentaires")
     */
    private $contenu;

    /**
     * @var \DateTime
     *@Groups("commentaires")
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var int
     * @Groups("commentaires")
     * @ORM\Column(name="nblike", type="integer", nullable=false)
     */
    private $nblike = '0';

    /**
     * @var int
     *@Groups("commentaires")
     * @ORM\Column(name="nbdislike", type="integer", nullable=false)
     */
    private $nbdislike = '0';

    /**
     * @var \Sujet
     *
     * @ORM\ManyToOne(targetEntity="Sujet")
     *
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idsujet", referencedColumnName="idsujet")
     * })
     */
    private $idsujet;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="id")
     * })
     */
    private $iduser;

    public function getIdcom(): ?int
    {
        return $this->idcom;
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

    public function getNblike(): ?int
    {
        return $this->nblike;
    }

    public function setNblike(int $nblike): self
    {
        $this->nblike = $nblike;

        return $this;
    }

    public function getNbdislike(): ?int
    {
        return $this->nbdislike;
    }

    public function setNbdislike(int $nbdislike): self
    {
        $this->nbdislike = $nbdislike;

        return $this;
    }

    public function getIdsujet(): ?Sujet
    {
        return $this->idsujet;
    }

    public function setIdsujet(?Sujet $idsujet): self
    {
        $this->idsujet = $idsujet;

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
