<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Sujet;
use App\Form\SujetType;
use App\Repository\SujetRepository;
use App\Repository\TopicRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use App\Entity\Topic;
/**
 * @Route("/sujet")
 */
class SujetController extends AbstractController
{
    /**
     * @Route("/{idtopic}", name="app_sujet_index", methods={"GET"})
     */
    public function index(SujetRepository $sujetRepository,Topic $topic): Response
    {
        return $this->render('topic/show.html.twig', [
            'topic' => $topic, 'sujets' => $sujetRepository->findByidtopic($topic->getIdtopic()),'sujetssort'=>$sujetRepository->findBy(['idtopic'=>$topic->getIdtopic()],['nbcom'=>'desc'])
        ]);
    }
    /**
     * @Route("/admin/{idtopic}", name="app_sujet_indexb", methods={"GET"})
     */
    public function indexb(SujetRepository $sujetRepository,Topic $topic): Response
    {
        return $this->render('sujet/indexb.html.twig', [
            'topic' => $topic, 'sujets' => $sujetRepository->findByidtopic($topic->getIdtopic()),
        ]);
    }
    /**
     * @Route("/{idtopic}/new", name="app_sujet_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SujetRepository $sujetRepository,Topic $topic,UtilisateurRepository $userRepository): Response
    {
        $sujet = new Sujet();
        $user =new Utilisateur();
        $user=$userRepository->find(33);
        dump($topic);
        $sujet->setIdtopic($topic);
        $sujet->setDate(new \DateTime('now'));
        $sujet->setIduser($user);
        $form = $this->createForm(SujetType::class, $sujet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sujetRepository->add($sujet);


            return $this->redirectToRoute('app_sujet_index', ['idtopic'=> $topic->getIdtopic()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sujet/new.html.twig', [
            'sujet' => $sujet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idtopic}/{idsujet}", name="app_sujet_show", methods={"GET"})
     */
    public function show(Sujet $sujet,Topic $topic): Response
    {
        $commentaires=$this->getDoctrine()->getRepository(Commentaire::class)->findByidsujet($sujet->getIdsujet());
        return $this->render('sujet/show.html.twig', [
            'sujet' => $sujet,'topic'=>$topic,'commentaires'=>$commentaires
        ]);
    }
    /**
     * @Route("/admin/{idtopic}/{idsujet}", name="app_sujet_showb", methods={"GET"})
     */
    public function showb(Sujet $sujet,Topic $topic): Response
    { $commentaires=$this->getDoctrine()->getRepository(Commentaire::class)->findByidsujet($sujet->getIdsujet());
        return $this->render('sujet/showb.html.twig', [
            'sujet' => $sujet,'topic'=>$topic,'commentaires'=>$commentaires
        ]);
    }

    /**
     * @Route("/{idsujet}/{user}/edit/", name="app_sujet_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Sujet $sujet, SujetRepository $sujetRepository,Utilisateur $user): Response
    {
        $form = $this->createForm(SujetType::class, $sujet);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sujetRepository->add($sujet);
            return $this->redirectToRoute('app_sujet_index', ['idtopic'=> $sujet->getIdtopic()->getIdtopic()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sujet/edit.html.twig', [
            'sujet' => $sujet,
            'form' => $form->createView(),
            'topic'=> $sujet->getIdtopic(),
        ]);
    }

    /**
     * @Route("/admin/{idtopic}/{idsujet}", name="app_sujet_delete", methods={"POST"})
     */
    public function delete(Request $request, Sujet $sujet, SujetRepository $sujetRepository,Topic $topic): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sujet->getIdsujet(), $request->request->get('_token'))) {
            $topic->setNbsujet($topic->getNbsujet()-1);
            $sujetRepository->remove($sujet);

        }

        return $this->redirectToRoute('app_sujet_indexb', ['idtopic'=> $topic->getIdtopic()], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/{idtopic}/{idsujet}/delete", name="app_sujet_deletef", methods={"GET"})
     */
    public function deletef(Request $request, Sujet $sujet, SujetRepository $sujetRepository,Topic $topic): Response
    {

            $topic->setNbsujet($topic->getNbsujet()-1);
            $sujetRepository->remove($sujet);



        return $this->redirectToRoute('app_topic_show', ['idtopic'=> $topic->getIdtopic()], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/{idtopic}/{idsujet}/accepter", name="app_sujet_accepter", methods={"GET"})
     */
    public function acceptersujet(Request $request, Sujet $sujet, SujetRepository $sujetRepository,Topic $topic): Response
    {
        if($topic->getAccepter()==1 &&$sujet->getAccepter()==0)
            $topic->setNbsujet($topic->getNbsujet()+1);

        $sujet->setAccepter(1);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('app_sujet_indexb', ['idtopic'=> $topic->getIdtopic()], Response::HTTP_SEE_OTHER);
    }
}
