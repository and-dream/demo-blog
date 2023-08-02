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

    //une méthode peut avoir 2 routes comme ici
        #[Route('/blog/modifier/{id}' , name:"blog_modifier")]

        /*dans le render en 2ème argument on peut envoyer des données dans la vue (twig) sous forme de tableau avec indice = >valeur 
        l'indice étant le nom de la variable dans le fichier twig et valeur sa valeur réelle
        */
    
        // ici on a créé une route et une méthode
        #[Route('/blog/ajout', name:"blog_ajout")]
        public function form(Request $globals, EntityManagerInterface $manager, Article $article = null) :Response
        {

            if($article == null)
            {
                $article =  new Article;
            }
            // dd($article);
            


            // $article = new Article();     //on va ratacher cet objet à une entity

            
            
            //creer un formulaire en utilisant une méthode AbstractController avec 1 argument
            $form = $this->createForm(ArticleType::class, $article);   //ratacher $article au formulaire

            //on veut récupérer tous les inputs et on veut les vérifier, on va utiliser une méthode vu que notre form est un objet
            //la variable $globals contient les superglobales
            //* handleRequest() permet de récupérer toutes les données de mes inputs
            $form->handleRequest($globals);

            //vérifier si l'utilisateur a cliqué sur Envoyer
            if($form->isSubmitted()&& $form ->isValid())
            {
                //ce qui est en POST c'est la méthode request de l'objet globals
                // dd($globals->request); 
                $article->setCreatedAt(new \Datetime);
                
                // dd($article);

                //on met en paramètre notre objet $article qui contient les données à envoyer en bdd
                //*persist() va permettre de préparer ma requête SQL a envoyer par rapport à l'objet donné en argument
                $manager->persist($article); 

                //* flush va permettre d'exécuter tout les persist précédents
                $manager->flush();

                //redirection, on va faire un return et utiliser une méthode de AbstractController, il faut lui donner un nom de route
                //* redirectToRoute() permet de rediriger vers une autre page de  notre site à l'aide du nom de la route (name)
                return $this->redirectToRoute('blog_gestion'); 

            }


            //ici on va render notre page twig
            return $this->render('blog/form.html.twig', [                 
                'formArticle' => $form,
                'editMode' => $article->getId() !== null   
                //si je suis en modification je vais avoir un chiffre, si je suis en ajout je vais avoir Null
                // si je suis en ajout ça sera différent
            ]);        
        }

        #[Route('/blog/gestion', name:'blog_gestion')]
        public function gestion(ArticleRepository $repo)   //récupérer les articles
        {
            $articles = $repo->findAll();
            return $this->render('blog/gestion.html.twig', [    //afficher les articles sur la page
                'articles' => $articles,
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

        //nouvelle route pour la suppression
        //il va falloir savoir quel article supprimer donc on aura besoin de l'id
        //le mananger nous permet de faire un ajout, une modification ou une suppression dans la bdd

        #[Route('/blog/supprimer/{id}', name: 'blog_supprimer')]
        public function supprimer(Article $article, EntityManagerInterface $manager)   //il nous faut le manager qui va nous permettre de faire une suppression

        {
            $manager->remove($article);   //on prepare 
            $manager->flush();     // on exécute la requête

            // il nous restera à renvoyer vers une page (on ne va pas créer une nouvelle page mais faire une redirection)
            return $this->redirectToRoute('blog_gestion'); // redirection
        }

        //on va insérer le nom de la route 'blog_supprimer' dans le lien dans la page de gestion des articles
        // <a href="{{ path('blog_supprimer', {id: article.id} )}}" class="text-danger">supprimer</a>

    }



