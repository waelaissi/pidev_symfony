<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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


}
