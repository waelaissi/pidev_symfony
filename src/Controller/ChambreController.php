<?php

namespace App\Controller;

use App\Entity\Chambre;
use App\Form\ChambreType;
use App\Repository\ChambreRepository;
use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/chambre")
 */
class ChambreController extends AbstractController
{
    /**
     * @Route("/", name="app_chambre_index", methods={"GET"})
     */
    public function index(ChambreRepository $chambreRepository): Response
    {
        return $this->render('chambre/chambreAdmin.html.twig', [
            'chambres' => $chambreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="app_chambre_new", methods={"GET", "POST"})
     */
    public function new(int $id,Request $request, ChambreRepository $chambreRepository, HotelRepository $hotelrepo): Response
    {
        $chambre = new Chambre();

        $chambre->setIdHotel($hotelrepo->find($id));
        $chambre->getDisponnibiliter(1);
        $form = $this->createFormBuilder($chambre)

            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Single' => "Single",
                    'Double' => "Double",
                    'Triple' => "Triple",
                    'Quadruple' => "Quadruple",
                ],
            ])
            ->add('prix')
            ->add('Ajouter',SubmitType::class, ['attr' => ['class' => 'btn btn-info btn-block',
                                                                     'formnovalidate' => 'formnovalidate'   ]])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chambreRepository->add($chambre);
            $this->addFlash('success', 'Chambre ajouter avec succées !');
            return $this->redirectToRoute('app_chambre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chambre/new.html.twig', [
            'chambre' => $chambre,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="app_chambre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Chambre $chambre, ChambreRepository $chambreRepository): Response
    {
        $form = $this->createFormBuilder($chambre)

            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Single' => "Single",
                    'Double' => "Double",
                    'Triple' => "Triple",
                    'Quadruple' => "Quadruple",
                ],
            ])
            ->add('prix')
            ->add('Modifier',SubmitType::class, ['attr' => ['class' => 'btn btn-info btn-block']])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chambreRepository->add($chambre);
            $this->addFlash('success', 'Chambre modifié avec succées !');
            return $this->redirectToRoute('app_chambre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chambre/edit.html.twig', [
            'chambre' => $chambre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_chambre_delete", methods={"POST"})
     */
    public function delete(Request $request, Chambre $chambre, ChambreRepository $chambreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chambre->getId(), $request->request->get('_token'))) {
            $chambreRepository->remove($chambre);
            $this->addFlash('success', 'Chambre supprimé avec succées !');
        }

        return $this->redirectToRoute('app_chambre_index', [], Response::HTTP_SEE_OTHER);
    }
}
