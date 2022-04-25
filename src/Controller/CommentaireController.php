<?php

namespace App\Controller;
use Symfony\Component\Mime\Email;

use App\Entity\Commentaire;
use App\Entity\Dislikee;
use App\Entity\Likee;
use App\Entity\Sujet;
use App\Entity\Topic;
use App\Entity\Utilisateur;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Doctrine\Bundle\DoctrineBundle\Mapping\EntityListenerServiceResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;

/**
 * @Route("/commentaire")
 */
class CommentaireController extends AbstractController
{
    /**
     * @Route("/", name="app_commentaire_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $commentaires = $entityManager
            ->getRepository(Commentaire::class)
            ->findAll();

        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaires,
        ]);
    }

    /**
     * @Route("/{idtopic}/{idsujet}/new", name="app_commentaire_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,Topic $idtopic,Sujet $idsujet,MailerInterface $mailer): Response
    {

        $commentaire = new Commentaire();
        $commentaire->setIduser($this->getDoctrine()->getRepository(Utilisateur::class)->find(33));
        $commentaire->setIdsujet($idsujet);
        $commentaire->setDate(new \DateTime('now'));

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $idsujet->setNbcom($idsujet->getNbcom()+1);
            $entityManager->persist($commentaire);
            $entityManager->flush();
            $commentaire1 = new Commentaire();
            $formc = $this->createForm(CommentaireType::class, $commentaire1);
            $commentaires=$this->getDoctrine()->getRepository(Commentaire::class)->findByidsujet($idsujet->getIdsujet());

            $email = (new Email())
                ->from('firas.chkoundali@esprit.tn')
                ->to($commentaire->getIduser()->getEmail())
                ->subject('Mail de Notification!')
                ->text("quelqu'un a fait un commentaire dans votre sujet ");
            $mailer->send($email);
            return $this->render('commentaire/new.html.twig', [
                'sujet' => $idsujet,'topic'=>$idtopic,'commentaires'=>$commentaires,  'commentaire' => $commentaire,
                'formcom' => $formc->createView(),
            ]);
        }

        $commentaires=$this->getDoctrine()->getRepository(Commentaire::class)->findByidsujet($idsujet->getIdsujet());
        return $this->render('commentaire/new.html.twig', [
            'sujet' => $idsujet,'topic'=>$idtopic,'commentaires'=>$commentaires,  'commentaire' => $commentaire,
            'formcom' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idcom}", name="app_commentaire_show", methods={"GET"})
     */
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    /**
     * @Route("/{idtopic}/{idsujet}/{idcom}/edit", name="app_commentaire_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager,Topic $idtopic,Sujet $idsujet): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $commentaire->setDate(new \DateTime('now'));
        $form->handleRequest($request);
        $commentaires=$this->getDoctrine()->getRepository(Commentaire::class)->findByidsujet($idsujet->getIdsujet());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $commentaire1 = new Commentaire();
            $formc = $this->createForm(CommentaireType::class, $commentaire1);
            return $this->redirectToRoute('app_commentaire_new', ['idtopic'=> $idtopic->getIdtopic(),'idsujet'=>$idsujet->getIdsujet()], Response::HTTP_SEE_OTHER);


        }

        return $this->render('commentaire/edit.html.twig', [
            'sujet' => $idsujet,'topic'=>$idtopic,'commentaires'=>$commentaires,'commentaire' => $commentaire,
            'formcomedit' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{idsujet}/{idcom}", name="app_commentaire_delete", methods={"POST"})
     */
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager,Sujet $idsujet): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getIdcom(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sujet_showb', ['idtopic'=>$idsujet->getIdtopic()->getIdtopic(),'idsujet'=>$idsujet->getIdsujet()], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/{idtopic}/{idsujet}/{idcom}/delete", name="app_commentaire_deletef", methods={"GET"})
     */
    public function deletef(Request $request, Commentaire $idcom, EntityManagerInterface $entityManager,Sujet $idsujet,Topic $idtopic): Response
    {
        $idsujet->setNbcom($idsujet->getNbcom()-1);
            $entityManager->remove($idcom);
            $entityManager->flush();
        return $this->redirectToRoute('app_commentaire_new', [ 'idsujet' => $idsujet->getIdsujet(),'idtopic'=>$idtopic->getIdtopic()], Response::HTTP_SEE_OTHER);


    }
    /**
     * @Route("/{idtopic}/{idsujet}/{idcom}/like", name="app_commentaire_like", methods={"GET"})
     */
    public function like(Request $request, Commentaire $idcom, EntityManagerInterface $entityManager,Sujet $idsujet,Topic $idtopic): Response
    {        $dislikeee=$this->getDoctrine()->getRepository(Dislikee::class)->findoneBy(['idCommentaire'=>$idcom->getIdcom(),'idUser'=>33]);
        $likeee=$this->getDoctrine()->getRepository(likee::class)->findBy(['idCommentaire'=>$idcom->getIdcom(),'idUser'=>33]);
    if($likeee==null){

        $idcom->setNblike($idcom->getNblike()+1);
        $like = new likee();
        $like->setIdCommentaire($idcom);
        $user=$this->getDoctrine()->getRepository(Utilisateur::class)->find(33);
        $like->setIdUser($user);
        $entityManager->persist($like);
        if($dislikeee!=null)
        {
            $idcom->setNbdislike($idcom->getNbdislike()-1);
            $entityManager->remove($dislikeee);

        }
        $entityManager->flush();}
    else
    {
        dump($likeee);
        $this->addFlash("error", "vous avez deja lkee ce commentaire");
    }
        return $this->redirectToRoute('app_commentaire_new', [ 'idsujet' => $idsujet->getIdsujet(),'idtopic'=>$idtopic->getIdtopic()], Response::HTTP_SEE_OTHER);


    }
    /**
     * @Route("/{idtopic}/{idsujet}/{idcom}/dislike", name="app_commentaire_dislike", methods={"GET"})
     */
    public function dislike(Request $request, Commentaire $idcom, EntityManagerInterface $entityManager,Sujet $idsujet,Topic $idtopic): Response
    {
        $dislikeee=$this->getDoctrine()->getRepository(Dislikee::class)->findBy(['idCommentaire'=>$idcom->getIdcom(),'idUser'=>33]);
        $likeee=$this->getDoctrine()->getRepository(Likee::class)->findOneBy(['idCommentaire'=>$idcom->getIdcom(),'idUser'=>33]);

        if($dislikeee==null){

            $idcom->setNbdislike($idcom->getNbdislike()+1);
            $dislike = new Dislikee();
            $dislike->setIdCommentaire($idcom);
            $user=$this->getDoctrine()->getRepository(Utilisateur::class)->find(33);
            $dislike->setIdUser($user);

            if($likeee!=null)
            {
                $idcom->setNblike($idcom->getNblike()-1);                $entityManager->remove($likeee);

            }
            $entityManager->persist($dislike);
            $entityManager->flush();}
        else
        {
            dump($dislikeee);
            $this->addFlash("error", "vous avez deja dislkee ce commentaire");
        }
        return $this->redirectToRoute('app_commentaire_new', [ 'idsujet' => $idsujet->getIdsujet(),'idtopic'=>$idtopic->getIdtopic()], Response::HTTP_SEE_OTHER);


    }

}
