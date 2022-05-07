<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegisterAgencierType;
use App\Form\RegisterHotelierType;
use App\Form\SendmailType;
use App\Repository\UtilisateurRepository;
use DiscordWebhook\Embed;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use \DiscordWebhooks\Client;
use \DiscordWebhook\EmbedColor;
use DiscordWebhook\Webhook;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;


/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/", name="app_land")
     */
    public function land( UtilisateurRepository $repository): Response
    {
        $user_activer= $repository->countUtilisateurByetat("activer");
        $user_desactiver= $repository->countUtilisateurByetat("desactiver");
        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['users', 'etat'],
                ['users activer',      $user_activer],
                ['users descativer',      $user_desactiver],


            ]
        );
        $pieChart->getOptions()->setHeight(300);
        $pieChart->getOptions()->setWidth(300);
        $pieChart->getOptions()->setColors(['#009900', '#990000', '#FF8C00']);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        $users = $this->getDoctrine()
            ->getRepository(Utilisateur::class)
            ->findAll();
        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'pieChart' => $pieChart,
        ]);

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
            $user->setRole("agencier");
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
    public function desactiverUser(UtilisateurRepository $repository,$id, EntityManagerInterface $entityManager)
    {
        $user= $repository->find($id);

        $user->setEtat("desactiver");


        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $webhook =new Webhook((array)'https://discord.com/api/webhooks/962166291127472158/0L-7NJz5tjIAO3_4D1osVu6jHoksEOoRovZro08XbYk8_fDvNJRXE3rq8g5d2RyKbykX');
        $embed = new \DiscordWebhook\Embed();
        $embed
            ->setTitle('desactiver')
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
     * @return RedirectResponse
     * @Route("/activer/{id}",name="activerUser")
     */
    public function activerUser(UtilisateurRepository $repository,$id, EntityManagerInterface $entityManager)
    {
        $user= $repository->find($id);
        $user->setEtat("activer");

      $repository->add($user);

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
     * @param UtilisateurRepository $repository
     * @return Response
     * @Route("/stat",name="usersstat")
     */
    public function usersStat(UtilisateurRepository $repository)
    {
        $user_activer= $repository->countUtilisateurByetat("activer");
        $user_desactiver= $repository->countUtilisateurByetat("desactiver");
        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['users', 'etat'],
                ['users activer',      $user_activer],
                ['users descativer',      $user_desactiver],
            ]
        );
        $pieChart->getOptions()->setHeight(300);
        $pieChart->getOptions()->setWidth(300);
        $pieChart->getOptions()->setColors(['#009900', '#990000', '#FF8C00']);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
        $users = $this->getDoctrine()
            ->getRepository(Utilisateur::class)
            ->findAll();
        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'pieChart' => $pieChart,
        ]);

    }

    /**
     * @Route("/email/{email_use}",name="sendMailToUser")
     */
    public function sendEmail(MailerInterface $mailer,Request $request,$email_use): Response
    {
        $form =$this->createForm(SendmailType::class,null);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $message=$form->get('message')->getData();
            $subject=$form->get('subject')->getData();
            $email = (new Email())
                ->from('chaker.ayachi@esprit.com')
                ->to((string)$email_use)
                ->subject((string)$subject)
                ->text('Sending emails is fun again!')
                ->html("<p>$message</p>");
            $mailer->send($email);
            $this->addFlash('success', 'votre email a ete bien envoyer');
            return $this->redirectToRoute('afficheall');
        }
        return $this->render('admin/sendMail.html.twig', ['form' => $form->createView(),'user_email'=>$email_use]);
    }


    /**
     * @return Response
     * @Route("/testsende",name="testsend")
     */
    public function send(UtilisateurRepository $repository)
    {
        $users = $repository->findAll();
        return $this->render('admin/testSend.html.twig', ['users' => $users]

        );
    }



    public function getRealEntities($users): array
    {
        foreach($users as $user)
        {
            $realEntities[$user->getIdU()] = [$user->getEmail() ];
        }
        return $realEntities;
    }
    public function searchAction(Request $request, UtilisateurRepository $repository )
    {
        $req_string=$request->get('searchValue');

        $users=$repository->findEntitiesByString($req_string);

        if(!$users)
        {
            $result['user']['error'] = "user introuvable";

        }
        else
        {
            $result['users'] = $this->getRealEntities($users);
        }
        return new Response(json_encode($result));
    }


    /**
     * @Route("/searchproduit", name="user_search", methods={"GET","POST"})
     * @throws ExceptionInterface
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
