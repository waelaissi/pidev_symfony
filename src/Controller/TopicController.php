<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Entity\Utilisateur;
use App\Form\TopicType;
use App\Repository\SujetRepository;
use App\Repository\TopicRepository;
use App\Repository\UtilisateurRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/topic")
 */
class TopicController extends AbstractController
{
    /**
     * @Route("/", name="app_topic_index", methods={"GET"})
     */
    public function index(TopicRepository $topicRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $topoics=$topicRepository->findAll();
        $topoicss = $paginator->paginate(
            $topoics,
            $request->query->getInt('page', 1),
            4
        );
        return $this->render('topic/index.html.twig', [
            'topics' => $topoicss,'topicssort'=>$topicRepository->findby([],['nbsujet' => 'desc'])
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
            'topics' => $topicRepository->findby([],['date'=>'desc']),
        ]);
    }

    /**
     * @Route("/admin/accepter/{idtopic}", name="app_topic_accepter", methods={"GET"})
     */
    public function acceptertopic(TopicRepository $topicRepository,Topic $idtopic): Response
    {
        $idtopic->setAccepter(1);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('app_topic_indexadmin', [], Response::HTTP_SEE_OTHER);
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
        $this->getDoctrine()->getManager()->flush();   return $this->redirectToRoute('app_topic_indexadmin', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/user/{iduser}", name="app_topic_user", methods={"GET"})
     */
    public function topicbyuser(Request $request,PaginatorInterface $paginator,TopicRepository $topicRepository,int $iduser): Response
    {
        $topoics=$topicRepository->findBy(['iduser'=>$iduser]);
        $topoicss = $paginator->paginate(
            $topoics,
            $request->query->getInt('page', 1),
            4
        );
        return $this->render('topic/index.html.twig', [
            'topics' => $topoicss,'topicssort'=>$topicRepository->findby([],['nbsujet' => 'desc'])
        ]);

    }
    /**
     * @Route("/searchtopic ", name="searchStudentx",methods={"GET"})
     */
    public function searchtopic(Request $request,NormalizerInterface $Normalizer)
{
$repository = $this->getDoctrine()->getRepository(Topic::class);
$requestString=$request->get('searchValue');
$topics = $repository->findtopicByNsc($requestString);
$jsonContent = $Normalizer->normalize($topics, 'json',['groups'=>'topics']);
$retour=json_encode($jsonContent);
return new Response($retour);
}





//mobilleeeee


    /**
     * @Route("/mobile/listetopics", name="mobile_liste_topic")
     */
    public function liste_topics(Request $request,NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Topic::class);
        $topics = $repository->findby([],['idtopic' => 'desc']);
        $jsonContent = $Normalizer->normalize($topics, 'json',['groups'=>'topics']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/mobile/topicbyid/{id}", name="mobile_topicbyid")
     */
    public function topicbyid(Request $request,$id,NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Topic::class);
        $topic = $repository->find($id);
        $jsonContent = $Normalizer->normalize($topic, 'json',['groups'=>'topics']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/mobile/delete/{id}", name="mobile_delete_topic")
     */
    public function deletetopic(Request $request,$id,NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Topic::class);
        $topic = $repository->find($id);
        $em= $this->getDoctrine()->getManager();
        $em->remove($topic);
        $em->flush();
        return new Response("topic supprimer avec succes");
    }
    /**
     * @Route("/mobile/modifier/{id}", name="mobile_modifier_topic")
     */
    public function modifiertopic(Request $request,$id,NormalizerInterface $Normalizer)
    {
        $em= $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Topic::class);
        $topic = $repository->find($id);
        $topic->setDescription($request->get('description'));
        $topic->setTitretopic($request->get('titre'));
        $em->flush();
        return new Response("topic modifier avec succes");
    }
    /**
     * @Route("/mobile/ajouter", name="mobile_ajouter_topic")
     */
    public function ajoutertopic(Request $request,NormalizerInterface $Normalizer,UtilisateurRepository $userRepository)
    {
        $em= $this->getDoctrine()->getManager();
        $topic=new Topic();
        $topic->setDescription($request->get('description'));
        $topic->setTitretopic($request->get('titre'));
        $topic->setImagename("téléchargement 1200");
        $user=$userRepository->find($request->get('iduser'));
        $topic->setDate(new \DateTime('now'));
        $topic->setIduser($user);
        $em->persist($topic);
        $em->flush();
        return new Response("topic ajouter avec succes");
    }



}
