<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Transaction
 *
 * @ORM\Table(name="transaction")
 * @ORM\Entity
 */
class Transaction
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_At", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt ;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 4,
     *      max = 50,
     *      minMessage = "The name must be at least {{ limit }} characters long",
     *      maxMessage = "The name cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(name="nom_carte", type="string", length=255, nullable=false)
     */
    private $nomCarte;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(
     *      min =16 ,
     *      minMessage = "Minimum length is 16",
     * )
     * @ORM\Column(name="numero_carte", type="string", length=255, nullable=false)
     */
    private $numeroCarte;

    /**
     * @var int
     * @Assert\NotBlank(message = "Required")
     * @ORM\Column(name="exp_mois", type="integer", nullable=false)
     */
    private $expMois;

    /**
     * @var int
     * @Assert\NotBlank(
     *      message = "Required",
     * )
     * @ORM\Column(name="exp_annee", type="integer", nullable=false)
     */
    private $expAnnee;

    /**
     * @var int
     * @Assert\NotBlank(
     *      message = "Required",
     * )
     * @Assert\Length(
     *      min =3 ,
     *      minMessage = "Minimum length is 3",
     * )
     * @ORM\Column(name="cvc", type="integer", nullable=false)
     */
    private $cvc;

    /**
     * @var int
     *
     * @ORM\Column(name="taux_avance", type="integer", nullable=false)
     */
    private $tauxAvance;

    /**
     * @var float
     *
     * @ORM\Column(name="montant_paye_avance", type="float", precision=10, scale=0, nullable=false)
     */
    private $montantPayeAvance;

    /**
     * @var string
     *
     * @ORM\Column(name="paymentIntent_id", type="string", length=255, nullable=false)
     */
    private $paymentintentId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }


    public function getNomCarte(): ?string
    {
        return $this->nomCarte;
    }

    public function setNomCarte(string $nomCarte): self
    {
        $this->nomCarte = $nomCarte;

        return $this;
    }

    public function getNumeroCarte(): ?string
    {
        return $this->numeroCarte;
    }

    public function setNumeroCarte(string $numeroCarte): self
    {
        $this->numeroCarte = $numeroCarte;

        return $this;
    }

    public function getExpMois(): ?int
    {
        return $this->expMois;
    }

    public function setExpMois(int $expMois): self
    {
        $this->expMois = $expMois;

        return $this;
    }

    public function getExpAnnee(): ?int
    {
        return $this->expAnnee;
    }

    public function setExpAnnee(int $expAnnee): self
    {
        $this->expAnnee = $expAnnee;

        return $this;
    }

    public function getCvc(): ?int
    {
        return $this->cvc;
    }

    public function setCvc(int $cvc): self
    {
        $this->cvc = $cvc;

        return $this;
    }

    public function getTauxAvance(): ?int
    {
        return $this->tauxAvance;
    }

    public function setTauxAvance(int $tauxAvance): self
    {
        $this->tauxAvance = $tauxAvance;

        return $this;
    }

    public function getMontantPayeAvance(): ?float
    {
        return $this->montantPayeAvance;
    }

    public function setMontantPayeAvance(float $montantPayeAvance): self
    {
        $this->montantPayeAvance = $montantPayeAvance;

        return $this;
    }

    public function getPaymentintentId(): ?string
    {
        return $this->paymentintentId;
    }

    public function setPaymentintentId(string $paymentintentId): self
    {
        $this->paymentintentId = $paymentintentId;

        return $this;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


}
