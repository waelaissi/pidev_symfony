<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Entity\Utilisateur;
use App\Form\TopicType;
use App\Repository\SujetRepository;
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
            'topics' => $topicRepository->findAll(),'topicssort'=>$topicRepository->findby([],['nbsujet' => 'desc'])
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
        $topic->setDate(new \DateTime('now'));
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
    public function show(Topic $topic,SujetRepository $sujetRepository): Response
    {

        return $this->render('topic/show.html.twig', [
            'topic' => $topic, 'sujets' => $sujetRepository->findByidtopic($topic->getIdtopic()),'sujetssort'=>$sujetRepository->findBy(['idtopic'=>$topic->getIdtopic()],['nbcom'=>'desc'])
        ]);

    }

    /**
     * @Route("/{idtopic}/edit", name="app_topic_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Topic $topic, TopicRepository $topicRepository): Response
    {


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






    /**
     * @Route("/delete/admin/{idtopic}/", name="app_topic_deletead", methods={"POST"})
     */
    public function delete(Request $request, Topic $topic, TopicRepository $topicRepository): Response
    {  // dump($this->getUser());

            if ($this->isCsrfTokenValid('delete' . $topic->getIdtopic(), $request->request->get('_token'))) {
                $topicRepository->remove($topic);
            }


            return $this->redirectToRoute('app_topic_indexadmin', [], Response::HTTP_SEE_OTHER);

    }
    /**
     * @Route("/delete/{idtopic}/", name="app_topic_delete", methods={"GET"})
     */
    public function deletef(Request $request, Topic $topic, TopicRepository $topicRepository): Response
    {  // dump($this->getUser());


            $topicRepository->remove($topic);



        return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);

    }
    /**
     * @Route("/admin/liste", name="app_topic_indexadmin", methods={"GET"})
     */
    public function indexadmin(TopicRepository $topicRepository): Response
    {
        return $this->render('topic/indexb.html.twig', [
            'topics' => $topicRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/accepter/{idtopic}", name="app_topic_accepter", methods={"GET"})
     */
    public function acceptertopic(TopicRepository $topicRepository,Topic $idtopic): Response
    {
        $idtopic->setAccepter(1);
        $this->getDoctrine()->getManager()->flush();
        return $this->render('topic/indexb.html.twig', [
            'topics' => $topicRepository->findAll(),
        ]);
    }
    /**
     * @Route("/admin/hide/{idtopic}", name="app_topic_hide", methods={"GET"})
     */
    public function hidetopic(TopicRepository $topicRepository,Topic $idtopic): Response
    {
        if($idtopic->getHide()==0 && $idtopic->getAccepter()==1){
        $idtopic->setHide(1);}
        else{
            $idtopic->setHide(0);
        }
        $this->getDoctrine()->getManager()->flush();
        return $this->render('topic/indexb.html.twig', [
            'topics' => $topicRepository->findAll(),
        ]);
    }

}
