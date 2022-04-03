<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Entity\Utilisateur;
use App\Form\TopicType;
use App\Repository\TopicRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/topic")
 */
class TopicController extends AbstractController
{
    /**
     * @Route("/", name="app_topic_index", methods={"GET"})
     */
    public function index(TopicRepository $topicRepository): Response
    {
        return $this->render('topic/index.html.twig', [
            'topics' => $topicRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_topic_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TopicRepository $topicRepository,UtilisateurRepository $userRepository): Response
    {
        $topic = new Topic();
        $user=$userRepository->find(33);
        dump($user);
        $topic->setIduser($user);
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $topicRepository->add($topic);
            return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('topic/new.html.twig', [
            'topic' => $topic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idtopic}", name="app_topic_show", methods={"GET"})
     */
    public function show(Topic $topic): Response
    {

        return $this->render('topic/show.html.twig', [
            'topic' => $topic,
        ]);
    }

    /**
     * @Route("/{idtopic}/{user}/edit", name="app_topic_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Topic $topic, TopicRepository $topicRepository,Utilisateur $user): Response
    {


            if($user->getId()==$topic->getIduser()->getId()){
                $form = $this->createForm(TopicType::class, $topic);
                $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $topicRepository->add($topic);
            return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('topic/edit.html.twig', [
            'topic' => $topic,
            'form' => $form->createView(),
        ]);
        }
    else{
        /*return new Response(

            "tu peux pas changer les topics des autres  "

        );*/
        return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
    }
    }




    /**
     * @Route("/{idtopic}", name="app_topic_delete", methods={"POST"})
     */
    public function delete(Request $request, Topic $topic, TopicRepository $topicRepository): Response
    {  // dump($this->getUser());

            if ($this->isCsrfTokenValid('delete' . $topic->getIdtopic(), $request->request->get('_token'))) {
                $topicRepository->remove($topic);
            }


        return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
    }
}
