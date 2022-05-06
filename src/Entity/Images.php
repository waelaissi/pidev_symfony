<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Images
 *
 * @ORM\Table(name="images", indexes={@ORM\Index(name="id_voiture", columns={"id_voiture"})})
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Images
{
    /**
     * @var int
     *
     * @ORM\Column(name="img_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $imgId;

    /**
     * @var string
     *
     * @ORM\Column(name="img_blob", type="string", length=100, nullable=false)
     */
    private $imgBlob;

    /**
     * @var \Voiture
     *
     * @ORM\ManyToOne(targetEntity="Voiture",inversedBy="images")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_voiture", referencedColumnName="id")
     * })
     */
    private $idVoiture;

    public function getImgId(): ?int
    {
        return $this->imgId;
    }

    public function getImgBlob(): ?string
    {
        return $this->imgBlob;
    }

    public function setImgBlob(string $imgBlob): self
    {
        $this->imgBlob = $imgBlob;

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
