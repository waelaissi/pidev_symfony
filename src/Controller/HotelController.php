<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Form\HotelType;
use App\Repository\HotelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * @Route("/hotel")
 */
class HotelController extends AbstractController
{
    /**
     * @Route("/", name="app_hotel_index", methods={"GET"})
     */
    public function index(HotelRepository $hotelRepository): Response
    {
        return $this->render('hotel/index.html.twig', [
            'hotels' => $hotelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_hotel_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $hotel = new Hotel();


        $form = $this->createFormBuilder($hotel)
            ->add('adresse', TextareaType::class)
            ->add('ville')
            ->add('region')
            ->add('numTel', IntegerType::class)
            ->add('description', TextareaType::class)
            ->add('libelle')
            ->add('nbEtoiles', IntegerType::class)
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image (JPG or PNG)',
                'required' => true,
                'allow_delete' => true,
                'download_uri' => false,
            ])
            ->add('Ajouter',SubmitType::class, ['attr' => ['class' => 'btn btn-info btn-block']])
            ->getForm();


        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //$hotel->setUser($this->getUser());
            $em->persist($hotel);
            $em->flush();

            $this->addFlash('success', 'Hotel ajouter avec succées !');

            return $this->redirectToRoute('app_hotel_index');
        }

        return $this->render('hotel/new.html.twig', [
            'hotel' => $hotel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_hotel_show", methods={"GET"})
     */
    public function show(Hotel $hotel): Response
    {
        return $this->render('hotel/show.html.twig', [
            'hotel' => $hotel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_hotel_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Hotel $hotel, HotelRepository $hotelRepository): Response
    {
        $form = $this->createFormBuilder($hotel)
            ->add('adresse', TextareaType::class)
            ->add('ville')
            ->add('region')
            ->add('numTel', IntegerType::class)
            ->add('description', TextareaType::class)
            ->add('libelle')
            ->add('nbEtoiles', IntegerType::class)
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image (JPG or PNG)',
                'required' => true,
                'allow_delete' => true,
                'download_uri' => false,
            ])
            ->add('Modifier',SubmitType::class, ['attr' => ['class' => 'btn btn-info btn-block']])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hotelRepository->add($hotel);
            $this->addFlash('info', 'Hotel modifier avec succées !');
            return $this->redirectToRoute('app_hotel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('hotel/edit.html.twig', [
            'hotel' => $hotel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_hotel_delete", methods={"POST"})
     */
    public function delete(Request $request, Hotel $hotel, HotelRepository $hotelRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hotel->getId(), $request->request->get('_token'))) {
            $hotelRepository->remove($hotel);
            $this->addFlash('error', 'Hotel supprimer avec succées !');
        }

        return $this->redirectToRoute('app_hotel_index', [], Response::HTTP_SEE_OTHER);
    }
}
