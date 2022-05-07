<?php

namespace App\Controller;

use App\Form\AgencierType;
use App\Form\ChangerPasswordType;
use App\Form\ClientType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/agencier")
 */
class AgencierController extends AbstractController
{

    /**
     * @return Response
     * @Route("/",name="dashboardagencier")
     */
    public function index(): Response
    {
        return $this->render('agencier/index.html.twig', [
            'controller_name' => 'AgencierController',
        ]);
    }

    /**
     * @return Response
     * @Route("/info",name="infopersonnel")
     */
    public function information()
    {
    return $this->render('agencier/information_personnel_agencier.html.twig');
    }


    /**
     * @param $id
     * @param UtilisateurRepository $repository
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/updateagencier/{id}",name="agencierUpdate")
     */
    public function update($id, UtilisateurRepository $repository, Request $request)
    {
        $user = $repository->find($id);
        $form = $this->createForm(AgencierType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $last_image=$user->getImage();
            if ($file == null){
                $user->setImage($last_image);
                return $this->redirectToRoute('infopersonnel');
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
            return $this->redirectToRoute('infopersonnel');
        }
        return $this->render('agencier/modifier_profile_agencier.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     * @Route("/resetpass",name="resetpasswordagencier")
     */
    public function changerPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
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
                $this->addFlash('success', 'Votre mot de passe à bien été changé !');
                return $this->redirectToRoute('dashboardagencier');
            } else {
                $form->addError(new FormError('Ancien mot de passe incorrect'));
                $this->addFlash('notice', 'Ancien mot de passe incorrect');

            }
        }
        return $this->render('agencier/passwordChangeAgencier.html.twig', array(
            'form' => $form->createView(),
        ));
    }




}
