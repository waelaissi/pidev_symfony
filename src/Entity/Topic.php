<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vangrg\ProfanityBundle\Validator\Constraints as ProfanityAssert;

/**
 * Topic
 *
 * @ORM\Table(name="topic", indexes={@ORM\Index(name="fk_userid", columns={"iduser"})})
 * @ORM\Entity
 * @Vich\Uploadable
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
     *  @ProfanityAssert\ProfanityCheck
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 10,
     *      max = 50,
     *      minMessage = "Le titre doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Le titre ne peut pas dépasser {{ limit }} caractères"
     * )
     * @ORM\Column(name="titretopic", type="string", length=255, nullable=true)
     */
    private $titretopic;

    /**
     *  @ProfanityAssert\ProfanityCheck
     * @var string|null
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 15,
     *      max = 100,
     *      minMessage = "La description doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Le titre ne peut pas dépasser {{ limit }} caractères"
     * )
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
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="topic_image", fileNameProperty="imageName")
     *
     * @var File|null
     */
    private $imageFile;
    /**
     * @var string|null
     * @ORM\Column(name="imageName", type="string", length=255, nullable=true)
     */
    private $imageName;

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

    public function getImagename(): ?string
    {
        return $this->imageName;
    }

    public function setImagename(?string $imagename): self
    {
        $this->imageName = $imagename;

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
