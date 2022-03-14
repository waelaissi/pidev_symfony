<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table(name="transaction")
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
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
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     *
     * @ORM\Column(name="taux_avance", type="integer", nullable=false)
     */
    private $tauxAvance;

    /**
     * @var int
     *
     * @ORM\Column(name="taux_commission", type="integer", nullable=false)
     */
    private $tauxCommission;

    /**
     * @var int
     *
     * @ORM\Column(name="taux_garantie", type="integer", nullable=false)
     */
    private $tauxGarantie;

    /**
     * @var float
     *
     * @ORM\Column(name="montant_paye_avance", type="float", precision=10, scale=0, nullable=false)
     */
    private $montantPayeAvance;

    /**
     * @var float
     *
     * @ORM\Column(name="montant_commission", type="float", precision=10, scale=0, nullable=false)
     */
    private $montantCommission;

    /**
     * @var float
     *
     * @ORM\Column(name="montant_garantie", type="float", precision=10, scale=0, nullable=false)
     */
    private $montantGarantie;

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

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getTauxCommission(): ?int
    {
        return $this->tauxCommission;
    }

    public function setTauxCommission(int $tauxCommission): self
    {
        $this->tauxCommission = $tauxCommission;

        return $this;
    }

    public function getTauxGarantie(): ?int
    {
        return $this->tauxGarantie;
    }

    public function setTauxGarantie(int $tauxGarantie): self
    {
        $this->tauxGarantie = $tauxGarantie;

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

    public function getMontantCommission(): ?float
    {
        return $this->montantCommission;
    }

    public function setMontantCommission(float $montantCommission): self
    {
        $this->montantCommission = $montantCommission;

        return $this;
    }

    public function getMontantGarantie(): ?float
    {
        return $this->montantGarantie;
    }

    public function setMontantGarantie(float $montantGarantie): self
    {
        $this->montantGarantie = $montantGarantie;

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


}
