<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Likee
 *
 * @ORM\Table(name="likee", indexes={@ORM\Index(name="fk_com", columns={"id_commentaire"}), @ORM\Index(name="fk_user", columns={"id_user"})})
 * @ORM\Entity(repositoryClass="App\Repository\LikeeRepository")
 */
class Likee
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_like", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLike;

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
     * @var \Commentaire
     *
     * @ORM\ManyToOne(targetEntity="Commentaire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_commentaire", referencedColumnName="idcom")
     * })
     */
    private $idCommentaire;
    /**
     * @ORM\ManyToOne(targetEntity=Voiture::class, inversedBy="likees")
     */
    private $voiture;


    public function getIdLike(): ?int
    {
        return $this->idLike;
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

    public function getIdCommentaire(): ?Commentaire
    {
        return $this->idCommentaire;
    }

    public function setIdCommentaire(?Commentaire $idCommentaire): self
    {
        $this->idCommentaire = $idCommentaire;

        return $this;
    }


    public function getVoiture()
    {
        return $this->voiture;
    }


    public function setVoiture($voiture): void
    {
        $this->voiture = $voiture;
    }


}
