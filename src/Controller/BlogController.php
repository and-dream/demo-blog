<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    // une route est définie par 2 arguments : son chemin (/blog) et son nom"app_blog"
    // pour un redirect_route on utilisera le nom
    public function index(ArticleRepository $repo): Response
    {
        //on va créer une variable qui va stocker tous nos articles
        //on va utiliser la methode findAll() pour nous renvoyer tous nos articles
        //* $repo est une instance de la classe ArticleRepository et possède les 4 méthodes de base find(), findOneBy(), findAll(), findBy() pour faire des requêtes SQL

        $articles = $repo->findAll();
        
        //afficher les articles
        // données qu'on va envoyer dans notre page de vue entre crochets, le nom de la variable et la valeur de la variable
        return $this->render('blog/index.html.twig', [
            "articles" => $articles
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
          'title' => 'Bienvenue sur mon blog',         // utiliser des variables sur la page, ça nous permet d'afficher les valeurs
          'age' => 22,  
        ]);  
    }   

        //j'aurais besoin d'ArticleRepository pour accéder à toutes les infos en bdd
        //notre chemin on peut lui passer un argument pour récupérer la valeur on déclare la variable où seront stockés les infos
        //si je lui défini un tableau en lui disant qu'il y a un champ id où on peut récupérer des données
        //créer une variable qui va contenir mon article en entier avec comme valeur la méthode find
        
        #[Route('/blog/show/{id}', name:"blog_show")]
        public function show($id, ArticleRepository $repo)
        {
            $article = $repo->find($id);
            return $this->render('blog/show.html.twig', [
                'article' => $article,
            ]);
        }

/**
     * !pour récupérer un article par son id on a 2 méthodes
     * *la première :
     *      *on a besoin de l'id en paramètre de la route 
     *         ! #[Route('/chemin/{id}', name:'nomRoute')]
     *      *on récupère la valeur de l'id dans la méthode et on récupère le Repository nécessaire
     *          ! public function nomFonction($id,   MonRepository $repo)
     *  *derrrière on peut utiliser la méthode find() de mon repo pour récupérer un élément avec son id
     *          ! $uneVariable = $repo->find($id);
     * *la deuxième :
     *      *on a besoin de l'id en paramètre de la Route
     *      ! #[Route('/chemin/{id}', name:'nomRoute')]
     *      * on va déclarer dans la méthode en paramètre l'entity que l'on veut récupérer
     *      ! public function nomFonction(MonEntity $monEntity)
     * 
     */  



    }



