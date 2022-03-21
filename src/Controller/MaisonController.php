<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Maison;
use App\Form\MaisonType;
use App\Form\SearchForm;
use App\Repository\MaisonRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Form\Type\VichImageType;


/**
 * @Route("/maison")
 */
class MaisonController extends AbstractController
{
    /**
     * @Route("/", name="app_maison_index", methods={"GET"})
     */
    public function index(MaisonRepository $maisonRepository): Response
    {
        return $this->render('maison/index.html.twig', [
            'maisons' => $maisonRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_maison_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MaisonRepository $maisonRepository, MailerInterface $mailer): Response
    {
        $maison = new Maison();
        $form = $this->createFormBuilder($maison)
                ->add('adresse', TextareaType::class)
                ->add('region')
                ->add('numTel', IntegerType::class)
                ->add('description', TextareaType::class)
                ->add('capacite', IntegerType::class)
                ->add('nbChambres', IntegerType::class)
                ->add('prix', MoneyType::class)
                ->add('imageFile', VichImageType::class, [
                    'label' => 'Image (JPG or PNG)',
                    'required' => true,
                    'allow_delete' => true,
                    'download_uri' => false,
                    'imagine_pattern' => 'squared_thumbnail_small',
                ])
                ->add('Ajouter',SubmitType::class, ['attr' => ['class' => 'btn btn-info btn-block']])
        ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$maison->getIdUser($this->getUser());
            $maisonRepository->add($maison);

            //sending email confirmation
            $email = (new Email())
                ->from('Tfarhida@tfarhida.tn')
                //->to($maison->getIdUser()->getEmail())
                ->to('you@example.com')
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->html('<p>Merci d ajouter votre maison dans notre application!</p>');

            $mailer->send($email);

            return $this->redirectToRoute('app_maison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('maison/new.html.twig', [
            'maison' => $maison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_maison_show", methods={"GET"})
     */
    public function show(Maison $maison): Response
    {
        return $this->render('maison/show.html.twig', [
            'maison' => $maison,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_maison_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Maison $maison, MaisonRepository $maisonRepository): Response
    {
        $form = $this->createFormBuilder($maison)
            ->add('adresse', TextareaType::class)
            ->add('region')
            ->add('numTel', IntegerType::class)
            ->add('description', TextareaType::class)
            ->add('capacite', IntegerType::class)
            ->add('nbChambres', IntegerType::class)
            ->add('prix', MoneyType::class)
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image (JPG or PNG)',
                'required' => true,
                'allow_delete' => true,
                'download_uri' => false,
                'imagine_pattern' => 'squared_thumbnail_small',
            ])
            ->add('Modifier',SubmitType::class, ['attr' => ['class' => 'btn btn-info btn-block']])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $maisonRepository->add($maison);
            return $this->redirectToRoute('app_maison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('maison/edit.html.twig', [
            'maison' => $maison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_maison_delete", methods={"POST"})
     */
    public function delete(Request $request, Maison $maison, MaisonRepository $maisonRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$maison->getId(), $request->request->get('_token'))) {
            $maisonRepository->remove($maison);
        }

        return $this->redirectToRoute('app_maison_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/all/maisons", name="app_maison_client", methods={"GET"})
     */
    public function clientMaison(MaisonRepository $maisonRepository, Request $request): Response
    {

        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
        $maison = $maisonRepository->findSearch($data);
        return $this->render('maison/clientMaison.html.twig', [
            'maisons' => $maison,
            'form' => $form->createView()
        ]);
    }
}
