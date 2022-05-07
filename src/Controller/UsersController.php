<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="app_users")
     */
    public function index(): Response
    {
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    /**
     * @Route("/affiche/",name="homepage")
     */
    public function affiche(UtilisateurRepository $repository)
    {
        $users = $repository->findAll();
        return $this->render('admin/affiche_tous_utilisateurs.html.twig',['users'=>$users]);
    }


}
