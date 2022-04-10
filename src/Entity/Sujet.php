<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 * Sujet
 *
 * @ORM\Table(name="sujet", indexes={@ORM\Index(name="fk_iduser", columns={"iduser"}), @ORM\Index(name="fk_idtopic", columns={"idtopic"})})
 * @ORM\Entity
 * @Vich\Uploadable
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
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="topic_image", fileNameProperty="imageName")
     *
     * @var File|null
     */
    private $imageFile;
    /**
     * @var string|null
     *
     * @ORM\Column(name="imageName", type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     */
    public function setImageFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile;
    }


    /**
     * @var \Topic
     *
     * @ORM\ManyToOne(targetEntity="Topic")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idtopic", referencedColumnName="idtopic")
     * })
     */
    private $idtopic;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="id")
     * })
     */
    private $iduser;
    public function getImagename(): ?string
    {
        return $this->imageName;
    }

    public function setImagename(?string $imagename): self
    {
        $this->imageName = $imagename;

        return $this;
    }
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

    public function getIdtopic(): ?Topic
    {
        return $this->idtopic;
    }

    public function setIdtopic(?Topic $idtopic): self
    {
        $this->idtopic = $idtopic;

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
