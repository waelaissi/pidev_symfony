<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ChangePasswordFormType;
use App\Form\ChangerPasswordType;
use App\Form\ClientType;
use App\Form\PasswordType;
use App\Form\RegistrationFormType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{

    /**
     * @Route("/", name="account")
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }


    /**
     * @param UtilisateurRepository $repository
     * @param Request $request
     * @return Response
     * @Route("/updateclient/{id}",name="clientupdate")
     */
    public function update($id, UtilisateurRepository $repository, Request $request)
    {
        $user = $repository->find($id);
        $form = $this->createForm(ClientType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
           $last_image=$user->getImage();
           if ($file == null){
               $user->setImage($last_image);
               return $this->redirectToRoute('account');
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
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'Votre modification a ete effectuer avec sucess');
            return $this->redirectToRoute('account');
        }
        return $this->render('account/updateClient.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/reset",name="passwordReset")
     */
    public function editAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm(ChangerPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $request->request->get('changer_password')['oldPassword'];
            // Si l'ancien mot de passe est bon
            if ($passwordEncoder->isPasswordValid($user, $oldPassword)) {
                $newEncodedPassword = $passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData()
                );
                $user->setPassword($newEncodedPassword);
                $em->persist($user);
                $em->flush();
                $this->addFlash('notice', 'Votre mot de passe à bien été changé !');
                return $this->redirectToRoute('account');
            } else {
                $form->addError(new FormError('Ancien mot de passe incorrect'));
            }
        }
        return $this->render('account/passwordChange.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
