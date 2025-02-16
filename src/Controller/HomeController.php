<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('', name: 'home')]
class HomeController extends AbstractController
{
    #[Route('', name: '.homepage')]
    public function homepage(): Response {
        return $this->render('home/homepage.html.twig');
    }
}