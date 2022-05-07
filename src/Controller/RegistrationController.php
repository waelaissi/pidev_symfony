<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Repository\UtilisateurRepository;
use App\Security\EmailVerifier;
use DiscordWebhook\Webhook;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;



class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // get the image from the request


            //$file = $request->files->get('image')['image'];
            $file = $form->get('image')->getData();
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($uploads_directory , $filename);
            $user->setImage($filename);


            // encode the plain password
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setEtat("activer");
            $user->setRoles((array)"ROLE_USER");
            $user->setRole("client");

            $entityManager->persist($user);
            $entityManager->flush();

            // notfing with discord
            $webhook =new Webhook((array)'https://discord.com/api/webhooks/962166291127472158/0L-7NJz5tjIAO3_4D1osVu6jHoksEOoRovZro08XbYk8_fDvNJRXE3rq8g5d2RyKbykX');
            $embed = new \DiscordWebhook\Embed();
            $embed
                ->setTitle('New user has been added')
                ->setDescription("*******");
            $webhook
                ->setMessage('******')
                ->addEmbed($embed)
                ->send();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('contact@tfarhida.com', 'Tfarhida Contact'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_hello');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UtilisateurRepository $utilisateurRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $utilisateurRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_hello');
    }

    // ++++++++++++++++++++++++++++++++++++++++++mobile part ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @Route("/registerer", name="app_registere")
     * @Method("POST")
     * @throws ExceptionInterface
     */
    public function registere(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager ,  NormalizerInterface $normalizer): JsonResponse
    {
        $now = new \DateTime();
        $user = new Utilisateur();
        $user->setEmail($request->get('email'));
        $user->setPassword(
            $userPasswordEncoder->encodePassword(
                $user,
                $request->get('password')
            )
        );
        $user->setLogin($request->get('login'));
        $user->setNom($request->get('nom'));
        $user->setPrenom($request->get('prenom'));
        $user->setNumTel($request->get('numTel'));
        $user->setCin($request->get('cin'));
        $user->setAdresse($request->get('adresse'));
        $user->setImage($request->get('image'));
        $user->setRoles((array)"ROLE_USER");
        $user->setEtat("activer");
        $user->setIsVerified(1);
        $user->setRole("client");
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $jsonContent = $normalizer->normalize($user, 'json', ['groups' => 'post:read']);
        return new JsonResponse($jsonContent);

    }


    /**
     * @param Request $request
     * @return JsonResponse|Response
     * @throws ExceptionInterface
     * @Route("/signin",name="mobile_login")
     */
    public function signinAction(Request $request){
        $email = $request->query->get('email');
        $password = $request->query->get('password');
        $em = $this->getDoctrine()->getManager();
        //chercher l utilisateur avec l email passer dans la requet
        $user = $em->getRepository(Utilisateur::class)->findOneBy(['email'=>$email]);
        // si l utilisateur existe
        if ($user){
            // on test si le not de passe est correct
            if (password_verify($password,$user->getPassword())){
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted= $serializer->normalize($user);
                // dd($formatted)
                return new JsonResponse($formatted);

            }else{

                return  new Response("failed");
            }

        }else {

            return  new Response("failed");

        }
    }


    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return JsonResponse|Response
     * @Route("/userEdit",name="edit_mobile_user")
     */
    public function editUser(Request  $request , UserPasswordEncoderInterface $passwordEncoder){
        $id = $request->query->get('id');

        $nom= $request->query->get('nom');
        $password= $request->query->get('password');
        $em= $this->getDoctrine()->getManager();
        $user=$em->getRepository(Utilisateur::class)->find($id);

            $user->setNom($nom);
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $password
                )
            );
            $user->setIsVerified(1);

        $file = $request->get('image');
        $last_image=$user->getImage();
        if ($file == null){
            $user->setImage($last_image);
        }
        if( $file  ){
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $fileName = $originalFilename.'-'.uniqid().'.'.$file->guessExtension();
            try {
                $uploads_directory = $this->getParameter('uploads_directory');
                $file->move($uploads_directory , $fileName);
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
                $this->addFlash('warning', 'il ya un probleme lors de linsertion de votre image ');
            }
            $user->setImage($fileName);
        }
            try {
            $em= $this->getDoctrine() ->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse("sucess",200);
            }
            catch (\Exception $exception)
            {
                return new Response("exception dans  modification de l utilisateur".$exception->getMessage());
            }




    }



}
