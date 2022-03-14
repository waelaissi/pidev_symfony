<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dislikee
 *
 * @ORM\Table(name="dislikee", indexes={@ORM\Index(name="fk_comdislike", columns={"id_commentaire"}), @ORM\Index(name="fk_userdislike", columns={"id_user"})})
 * @ORM\Entity(repositoryClass="App\Repository\DislikeeRepository")
 */
class Dislikee
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_dislike", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDislike;

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
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;

    public function getIdDislike(): ?int
    {
        return $this->idDislike;
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

    public function getIdUser(): ?Utilisateur
    {
        return $this->idUser;
    }

    public function setIdUser(?Utilisateur $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }


}
