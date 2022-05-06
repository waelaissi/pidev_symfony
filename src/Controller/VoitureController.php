<?php

namespace App\Controller;

use App\Entity\Likee;
use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Repository\LikeeRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\VoitureRepository;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Images;


/**
 * @Route("/voiture")
 */
class VoitureController extends AbstractController
{
    /**
     * @Route("/", name="app_voiture_index", methods={"GET"})
     */
    public function index(VoitureRepository $voitureRepository ): Response
    {
        return $this->render('voiture/index.html.twig', [
            'voitures' => $voitureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_voiture_new", methods={"GET", "POST"})
     */
    public function new(Request $request, VoitureRepository $voitureRepository ): Response
    {
        $voiture = new Voiture();

        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voitureRepository->add($voiture);

            // On récupère les images transmises
            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Images();
                $img->setImgBlob($fichier);
                $voiture->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($voiture);
            $entityManager->flush();
            $this->addFlash('success', 'Voiture ajouté avec succées !');


            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('voiture/new.html.twig', [
            'voiture' => $voiture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_voiture_show", methods={"GET"})
     */
    public function show(Voiture $voiture): Response
    {
        return $this->render('voiture/show.html.twig', [
            'voiture' => $voiture,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_voiture_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Voiture $voiture, VoitureRepository $voitureRepository): Response
    {
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voitureRepository->add($voiture);
            // On récupère les images transmises
            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Images();
                $img->setImgBlob($fichier);
                $voiture->addImage($img);

            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($voiture);
            $entityManager->flush();

            $this->addFlash('info', 'Voiture modifié avec succées !');


            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('voiture/edit.html.twig', [
            'voiture' => $voiture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_voiture_delete", methods={"POST"})
     */
    public function delete(Request $request, Voiture $voiture, VoitureRepository $voitureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voiture->getId(), $request->request->get('_token'))) {
            $voitureRepository->remove($voiture);
            $this->addFlash('error', 'Voiture supprimé avec succées !');

        }
        return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/all/voitures", name="app_voiture_client", methods={"GET"})
     */
    public function clientVoiture(PaginatorInterface $paginator,VoitureRepository $voitureRepository, Request $request): Response
    {
        $voitures = $paginator->paginate(
            $voitureRepository->findAll(),
            $request->query->getInt('page', 1),
            2
        );

        return $this->render('voiture/clientVoiture.html.twig', [
            'voitures' => $voitures

        ]);
    }
    /**
     * @Route("/all/stat", name="stat")
     */
    public function statistiques(VoitureRepository $voitureRepository)
    {
        $count = $voitureRepository->countNombre();

        $marques= [];
        $countMarque= [];

        foreach ($count as $voiture) {
            $marques[] = $voiture['marque'];

            $countMarque[] = $voiture['counts'];
        }
        $countcat = $voitureRepository->countNombrecat();

        $libelle= [];
        $countcate= [];

        foreach ($countcat as $c) {
            $libelle[] = $c['libelle'];

            $countcate[] = $c['counts'];
        }

            return $this->render('voiture/stat.html.twig',[
        'marques' => json_encode($marques),
            'countMarques' => json_encode($countMarque),
                'libelle' => json_encode($libelle),
                'countcate' => json_encode($countcate),
                'nbr'=>$voitureRepository->countnbrvoiture()
]);

    }
    /**
     * @Route("/like/{id}", name="app_voiture_like", methods={"GET"})
     */
    public function like(Voiture $voiture, LikeeRepository $likeRepo, VoitureRepository $voitureRepository, UtilisateurRepository $ur)
    {
        $like = new Likee();
        $like->setVoiture($voiture);

        $like->setIdUser($ur->find(32));

        $likeRepo->add($like);

        return $this->redirectToRoute('app_voiture_wish');
    }

    /**
     * @Route("/wish/list", name="app_voiture_wish", methods={"GET"})
     */
    public function wishList(VoitureRepository $voitureRepository)
    {
        $wishs = array();
        $voitures = $voitureRepository->findAll();
        foreach ($voitures as $voiture){
            foreach ($voiture->getLikees() as $like){
                if($like->getIdUser()->getId()== 32){
                    array_push($wishs, $like->getVoiture());
                }
            }
        }

        return $this->render('voiture/wishList.html.twig', [
            'wishs' => $wishs,
        ]);
    }


}
