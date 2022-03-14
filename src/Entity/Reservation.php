<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="fk_resvoiture", columns={"id_voiture"}), @ORM\Index(name="id_maison", columns={"id_maison"}), @ORM\Index(name="fkchambre", columns={"id_chambre"}), @ORM\Index(name="id_ticket", columns={"id_ticket"}), @ORM\Index(name="id_transaction", columns={"id_transaction"}), @ORM\Index(name="id_user", columns={"id_user"})})
 * @ORM\Entity
 */
class Reservation
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_debut", type="date", nullable=true)
     */
    private $dateDebut;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_fin", type="date", nullable=true)
     */
    private $dateFin;

    /**
     * @var float
     *
     * @ORM\Column(name="montant_a_payer", type="float", precision=10, scale=0, nullable=false)
     */
    private $montantAPayer;

    /**
     * @var float|null
     *
     * @ORM\Column(name="reste_a_payer", type="float", precision=10, scale=0, nullable=true)
     */
    private $resteAPayer;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_ticket", type="integer", nullable=true)
     */
    private $idTicket;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_maison", type="integer", nullable=true)
     */
    private $idMaison;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_user", type="integer", nullable=true)
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255, nullable=false, options={"default"="confirmé"})
     */
    private $etat = 'confirmé';

    /**
     * @var int
     *
     * @ORM\Column(name="id_transaction", type="integer", nullable=false)
     */
    private $idTransaction;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20, nullable=false)
     */
    private $type;

    /**
     * @var \Chambre
     *
     * @ORM\ManyToOne(targetEntity="Chambre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_chambre", referencedColumnName="id")
     * })
     */
    private $idChambre;

    /**
     * @var \Voiture
     *
     * @ORM\ManyToOne(targetEntity="Voiture")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_voiture", referencedColumnName="id")
     * })
     */
    private $idVoiture;


}
