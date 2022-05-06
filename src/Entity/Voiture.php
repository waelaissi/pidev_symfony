<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Validator as ImmatAssert;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Mgilet\NotificationBundle\NotifiableInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Mgilet\NotificationBundle\Annotation\Notifiable;


/**
 * Voiture
 *
 * @ORM\Table(name="voiture", indexes={@ORM\Index(name="id_categorie", columns={"id_categorie"}), @ORM\Index(name="id_user", columns={"id_user"})})
 * @ORM\Entity(repositoryClass="App\Repository\VoitureRepository")
 * @Notifiable(name="Voiture")
 */
class Voiture implements NotifiableInterface
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
     * @var string|null
     *
     * @ORM\Column(name="model", type="string", length=255, nullable=true)
     * @Assert\NotNull(message="donner le model")
     * @Assert\Length(
     *      min = 2,
     *      max = 10,
     *      minMessage = "invalid model",
     *      maxMessage = "trés long model"
     * )
     */
    private $model;

    /**
     * @var string|null
     *
     * @ORM\Column(name="marque", type="string", length=255, nullable=true)
     * @Assert\NotNull(message="donner la marque")
     * @Assert\Type(type={"alpha"})
     */
    private $marque;

    /**
     * @var string|null
     *
     * @ORM\Column(name="couleur", type="string", length=255, nullable=true)
     * @Assert\NotNull(message="donner un couleur")
     * @Assert\Type(type={"alpha"})
     */
    private $couleur;

    /**
     * @var int|null
     *
     * @ORM\Column(name="capacite", type="integer", nullable=true)
     * @Assert\NotNull(message="donner le nombre des places")
     * @Assert\Positive
     * @Assert\Range(
     *      min = 2,
     *      max = 10,
     *      notInRangeMessage = "invalid capacite",
     * )
     */
    private $capacite;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     * @Assert\NotNull(message="donner une description")
     *  @Assert\Length(
     *      min = 2,
     *      max = 250,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "trés long description!"
     * )
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="immat", type="string", length=30, nullable=true)
     * @Assert\NotNull(message="donnée voiture immatricule")
     * @Assert\Length(
     *      min = 6,
     *      max = 9,
     *      minMessage = "the immat must be at least {{ limit }} characters long",
     *      maxMessage = " the immat cannot be longer than {{ limit }} characters"
     * )
     */
    private $immat;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     * @Assert\NotNull(message="donnée un prix")
     * @Assert\Type(
     *     type="float",
     *     message="prix unvalid"
     * )
     */
    private $prix;

    /**
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie", referencedColumnName="id")
     * })
     */
    private $idCategorie;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Images", mappedBy="idVoiture",cascade={"persist"})
     */
    private $images;
    /**
     * @ORM\OneToMany(targetEntity=Likee::class, mappedBy="voiture")
     */
    private $likees;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->likees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(?int $capacite): self
    {
        $this->capacite = $capacite;

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

    public function getImmat(): ?string
    {
        return $this->immat;
    }

    public function setImmat(?string $immat): self
    {
        $this->immat = $immat;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getIdCategorie(): ?Categorie
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(?Categorie $idCategorie): self
    {
        $this->idCategorie = $idCategorie;

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
    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setIdVoiture($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getIdVoiture() === $this) {
                $image->setIdVoiture(null);
            }
        }

        return $this;
    }
    /**
     * Get preview image
     *
     * @return Images
     */
    public function getfirstImage(): Images
    {

        return $this->images->first();
    }


    public function getLikees()
    {
        return $this->likees;
    }


    public function setLikees($likees): void
    {
        $this->likees = $likees;
    }



}
