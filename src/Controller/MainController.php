<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\HttpFoundation\Response;



class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/MainController.php',
        // ]);
        // return new Response('<h1>uuu </h1>'  );
        return $this->render('home/index.html.twig');
    }


    /**
     * @Route("/empty", name="empty")
     */
    public function empty()
    {
        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/MainController.php',
        // ]);
        return new Response();
    }


}
