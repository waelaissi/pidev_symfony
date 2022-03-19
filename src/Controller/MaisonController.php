<?php

namespace App\Controller;

use App\Entity\Maison;
use App\Form\MaisonType;
use App\Repository\MaisonRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Form\Type\VichImageType;


/**
 * @Route("/maison")
 */
class MaisonController extends AbstractController
{
    /**
     * @Route("/", name="app_maison_index", methods={"GET"})
     */
    public function index(MaisonRepository $maisonRepository): Response
    {
        return $this->render('maison/index.html.twig', [
            'maisons' => $maisonRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_maison_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MaisonRepository $maisonRepository): Response
    {
        $maison = new Maison();
        $form = $this->createFormBuilder($maison)
                ->add('adresse', TextareaType::class)
                ->add('region')
                ->add('numTel', IntegerType::class)
                ->add('description', TextareaType::class)
                ->add('capacite', IntegerType::class)
                ->add('nbChambres', IntegerType::class)
                ->add('prix', MoneyType::class)
                ->add('imageFile', VichImageType::class, [
                    'label' => 'Image (JPG or PNG)',
                    'required' => true,
                    'allow_delete' => true,
                    'download_uri' => false,
                    'imagine_pattern' => 'squared_thumbnail_small',
                ])
                ->add('Ajouter',SubmitType::class, ['attr' => ['class' => 'btn btn-info btn-block']])
        ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $maisonRepository->add($maison);
            return $this->redirectToRoute('app_maison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('maison/new.html.twig', [
            'maison' => $maison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_maison_show", methods={"GET"})
     */
    public function show(Maison $maison): Response
    {
        return $this->render('maison/show.html.twig', [
            'maison' => $maison,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_maison_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Maison $maison, MaisonRepository $maisonRepository): Response
    {
        $form = $this->createFormBuilder($maison)
            ->add('adresse', TextareaType::class)
            ->add('region')
            ->add('numTel', IntegerType::class)
            ->add('description', TextareaType::class)
            ->add('capacite', IntegerType::class)
            ->add('nbChambres', IntegerType::class)
            ->add('prix', MoneyType::class)
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image (JPG or PNG)',
                'required' => true,
                'allow_delete' => true,
                'download_uri' => false,
                'imagine_pattern' => 'squared_thumbnail_small',
            ])
            ->add('Modifier',SubmitType::class, ['attr' => ['class' => 'btn btn-info btn-block']])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $maisonRepository->add($maison);
            return $this->redirectToRoute('app_maison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('maison/edit.html.twig', [
            'maison' => $maison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_maison_delete", methods={"POST"})
     */
    public function delete(Request $request, Maison $maison, MaisonRepository $maisonRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$maison->getId(), $request->request->get('_token'))) {
            $maisonRepository->remove($maison);
        }

        return $this->redirectToRoute('app_maison_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/all/maisons", name="app_maison_client", methods={"GET"})
     */
    public function clientMaison(MaisonRepository $maisonRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $données = $maisonRepository->findAll();
        $maison = $paginator->paginate(
            $données,
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );
        $maison->setCustomParameters([
            'align' => 'center', # center|right (for template: twitter_bootstrap_v4_pagination and foundation_v6_pagination)
            'size' => 'large', # small|large (for template: twitter_bootstrap_v4_pagination)
            'style' => 'bottom',
            'span_class' => 'whatever',
        ]);
        return $this->render('maison/clientMaison.html.twig', [
            'maisons' => $maison,
        ]);
    }
}
