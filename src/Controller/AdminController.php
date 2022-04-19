<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegisterAgencierType;
use App\Form\RegisterHotelierType;
use App\Repository\UtilisateurRepository;
use DiscordWebhook\Embed;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use \DiscordWebhooks\Client;
use \DiscordWebhook\EmbedColor;
use DiscordWebhook\Webhook;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/", name="app_land")
     */
    public function land(): Response
    {
        return $this->render('test/index.html.twig');
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route ("/ajouterAgencier", name="agencier")
     */
    public function index(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegisterAgencierType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($uploads_directory , $filename);
            $user->setImage($filename);
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setEtat("activer");
            $user->setIsVerified(true);
            $user->setRoles((array)"ROLE_AGENCIER");
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'agencier ajouter avec sucess');
            return $this->redirectToRoute('afficheall');
        }


        return $this->render('admin/ajouterAgencier.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route ("/ajouterHotlier", name="hotlier")
     */
    public function ajouterHotelier(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager) :Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegisterHotelierType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($uploads_directory , $filename);
            $user->setImage($filename);
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setEtat("activer");
            $user->setIsVerified(true);
            $user->setRoles((array)"ROLE_HOTELIER");
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'hotelier ajouter avec sucess');
            return $this->redirectToRoute('afficheall');
        }


        return $this->render('admin/ajouterHotlier.html.twig', ['form' => $form->createView()]);

    }


    /**
     * @param UtilisateurRepository $repository
     * @return Response
     * @Route ("/afficheAll",name="afficheall")
     */
    public function affiche(UtilisateurRepository $repository)
    {
        $users = $repository->findAll();
        return $this->render('admin/affiche_tous_utilisateurs.html.twig', ['users' => $users]);

    }


    /**
     * @param UtilisateurRepository $repository
     * @param $id
     * @return Response
     * @Route("/desactiver/{id}",name="desactiverUser")
     */
    public function activerUser(UtilisateurRepository $repository,$id, EntityManagerInterface $entityManager)
    {
        $user= $repository->find($id);
        $user->setEtat("desactiver");
        $entityManager->persist($user);
        $entityManager->flush();
        $webhook =new Webhook((array)'https://discord.com/api/webhooks/962166291127472158/0L-7NJz5tjIAO3_4D1osVu6jHoksEOoRovZro08XbYk8_fDvNJRXE3rq8g5d2RyKbykX');
        $embed = new \DiscordWebhook\Embed();
        $embed
            ->setTitle('descativation')
            ->setDescription('you have banned '.$user->getNom().' '.$user->getPrenom()."\n email :".$user->getEmail()."\n phone Number :".$user->getNumTel());
        $webhook
            ->setMessage('un utilisateur a ete banner!')
            ->addEmbed($embed)
            ->send();
        return $this->redirectToRoute('afficheall');
    }

    /**
     * @param UtilisateurRepository $repository
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/activer/{id}",name="activerUser")
     */
    public function desactiverUser(UtilisateurRepository $repository,$id, EntityManagerInterface $entityManager)
    {
        $user= $repository->find($id);
        $user->setEtat("activer");
        $entityManager->persist($user);
        $entityManager->flush();
        $webhook =new Webhook((array)'https://discord.com/api/webhooks/962166291127472158/0L-7NJz5tjIAO3_4D1osVu6jHoksEOoRovZro08XbYk8_fDvNJRXE3rq8g5d2RyKbykX');
        $embed = new \DiscordWebhook\Embed();
        $embed
            ->setTitle('activation')
            ->setDescription('you have activated account '.$user->getNom().' '.$user->getPrenom()."\n email :".$user->getEmail()."\n phone Number :".$user->getNumTel());
        $webhook
            ->setMessage('un utlisateur est activer')
            ->addEmbed($embed)
            ->send();
        return $this->redirectToRoute('afficheall');
    }

    /**
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @param UtilisateurRepository $repository
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/searchuser",name="user_search")
     */
    public function searchProduit(Request $request,NormalizerInterface $Normalizer, UtilisateurRepository $repository)
    {

        $requestString=$request->get('searchValue');
        $user= $repository->findUtilidateurByNom($requestString);

        $jsonContent = $Normalizer->normalize($user, 'json',['groups'=>'post:read']);
        $retour=json_encode($jsonContent);
        return new Response($retour);
    }


}
