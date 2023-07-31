<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    // une route est définie par 2 arguments : son chemin (/blog) et son nom"app_blog"
    // pour un redirect_route on utilisera le nom
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController', // données qu'on va envoyer dans notre page de vue entre crochets, le nom de la variable et la valeur de la variable
        ]);
        //render permet d'afficher le contenu d'un template. Elle va chercher directement dans le dossier templates
        //$this-> fait référence à AbstractController
    }

    //on va créer une méthode, on va commencer par créer sa route

    #[Route('/', name:'home')]
    public function home() : response    //si il y a response il y a un return
    {
        //le retour du template
        return $this->render('blog/home.html.twig', [
          'title' => 'Bienvenue sur mon blog',
          'age' => 22,  
        ]);  
    }   /*dans le render en 2ème argument on peut envoyer des données dans la vue (twig) sous forme de tableau avec indice = >valeur 
        l'indice étant le nom de la variable dans le fichier twig et valeur sa valeur réelle
        */
    
}
