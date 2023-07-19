<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class LuckyController extends AbstractController{

    #[Route('/accueil', methods: ['GET'])]
    public function number(): Response
    {
        $page_name = "Bienvenue";
        $number = rand(1,10);
        return $this->render('base.html.twig', ['number' => $number, 'page_name' => $page_name]);
    }

}




?>  