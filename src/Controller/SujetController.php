<?php

namespace App\Controller;

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
            $topic->setNbsujet($topic->getNbsujet()+1);
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
        return $this->render('sujet/show.html.twig', [
            'sujet' => $sujet,'topic'=>$topic
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
     * @Route("/{idtopic}/{idsujet}", name="app_sujet_delete", methods={"POST"})
     */
    public function delete(Request $request, Sujet $sujet, SujetRepository $sujetRepository,Topic $topic): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sujet->getIdsujet(), $request->request->get('_token'))) {
            $topic->setNbsujet($topic->getNbsujet()-1);
            $sujetRepository->remove($sujet);

        }

        return $this->redirectToRoute('app_sujet_index', ['idtopic'=> $topic->getIdtopic()], Response::HTTP_SEE_OTHER);
    }
}