<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Form\IngredientsType;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\IngredientsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientsController extends AbstractController
{
    /**
     * This function display all ingredients
     *
     * @param IngredientsRepository $ingredientsRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredients', name: 'app_ingredients', methods: ['GET'])]
    public function index(IngredientsRepository $ingredientsRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $ingredients = $paginator->paginate(
            $ingredientsRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        

       

        return $this->render('pages/ingredients/ingredients.html.twig', ["ingredients" => $ingredients]);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param EntityManagerInterface $manage
     * @return Response
     */
    #[Route('/ingredients/form', name: 'ingredients.form', methods: ['GET', 'POST'])]
    public function form(Request $request, EntityManagerInterface $manage): Response{

        $ingredients = new Ingredients();
        //creer le formulaire
        $form = $this->createForm(IngredientsType::class, $ingredients);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredients = $form->getData();
 
            $manage->persist($ingredients);
            $manage->flush();

            //message flash
            $this->addFlash(
                'success',
                'Votre ingredient a été créé avec succès !'
            );

            return $this->redirectToRoute('app_ingredients');

        }else{

        }

        

        return $this->render('pages/ingredients/form.html.twig', ['form'=>$form->createView()]);
    }

    #[Route('/ingredients/edit/{id}', name: 'ingredients.edit', methods: ['GET', 'POST'])]
    public function edit(Ingredients $ingredients, Request $request, EntityManagerInterface $manage): Response{


        //creer le formulaire
        $form = $this->createForm(IngredientsType::class, $ingredients);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredients = $form->getData();
 
            $manage->persist($ingredients);
            $manage->flush();

            //message flash
            $this->addFlash(
                'success',
                'Votre ingredient a été modifié avec succès !'
            );

            return $this->redirectToRoute('app_ingredients');

        }else{

        }
            
            return $this->render('pages/ingredients/edit.html.twig', ['form'=>$form->createView()]);
    }

    #[Route('/ingredients/supprimer/{id}', name: 'ingredients.delete', methods: ['GET', 'POST'])]
    public function delete(Ingredients $ingredients,EntityManagerInterface $manage): Response{

        $manage->remove($ingredients);
        $manage->flush();

        $this->addFlash(
            'success',
            'L\'ingredient a été supprimé avec succès !'
        );

        return $this->redirectToRoute('app_ingredients');
    }
            
            
    }


