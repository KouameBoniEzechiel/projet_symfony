<?php

namespace App\Controller;


use App\Entity\Ingredients;
use App\Entity\Recettes;
use App\Form\RecettesType;
use App\Repository\RecettesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecettesController extends AbstractController
{
    #[Route('/recettes', name: 'recettes.index')]
    public function index(RecettesRepository $recipeRepository, PaginatorInterface $paginator, Request $request): Response
    {


        $recipes = $paginator->paginate(
            $recipeRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );


        return $this->render('pages/recipe/index.html.twig', ['recipes' => $recipes]);
    }

    #[Route('/recettes/create', name: 'recettes.create', methods: ['GET', 'POST'])]
    public function createRecette(Request $request, EntityManagerInterface $manage): Response{

        $recettes = new Recettes();
        //creer le formulaire
        $form = $this->createForm(RecettesType::class, $recettes);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recettes = $form->getData();
            $manage->persist($recettes);
            $manage->flush();   

            //message flash
            $this->addFlash(
                'success',
                'Votre recette a été créée avec succès !'
            );

           return $this->redirectToRoute('recettes.index'); 
        }

        

        return $this->render('pages/recipe/create.html.twig', ['form' => $form->createView()]);
    }

    //Modifier une recette
    #[Route('/recettes/edit/{id}', name: 'recettes.edit', methods: ['GET', 'POST'])]
    public function editRecette(Recettes $recettes, Request $request, EntityManagerInterface $manage): Response{

        //creer le formulaire
        $form = $this->createForm(RecettesType::class, $recettes);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recettes = $form->getData();
            $manage->persist($recettes);
            $manage->flush();   

            //message flash
            $this->addFlash(
                'success',
                'Votre recette a été modifiée avec succès !'
            );

           return $this->redirectToRoute('recettes.index'); 
        }

        

        return $this->render('pages/recipe/edit.html.twig', ['form' => $form->createView()]);
    }

    //supprimer une recette
    #[Route('/recettes/supprimer/{id}', name: 'recettes.delete', methods: ['GET', 'POST'])]
    public function deleteRecette(Recettes $recettes, Request $request, EntityManagerInterface $manage): Response{

        //creer le formulaire
        $manage->remove($recettes);
        $manage->flush();

            //message flash
            $this->addFlash(
                'success',
                'Votre recette a été supprimée avec succès !');

                return $this->redirectToRoute('recettes.index');
    }

    #[Route('/recettes/voir/{id}', name: 'recettes.plus')]
    public function voirPlus(RecettesRepository $recipeRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $id = $request->get('id');

        $recipes = $paginator->paginate(
            $recipeRepository->findBy(array('id' => $id)), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );


        return $this->render('pages/recipe/voir.html.twig', ['recipes' => $recipes]);
    }

}
