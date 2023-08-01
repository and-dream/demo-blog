<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
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
          'title' => 'Bienvenue sur mon blog',         // utiliser des variables sur la page, ça nous permet d'afficher les valeurs
          'age' => 22,  
        ]);  
    }   /*dans le render en 2ème argument on peut envoyer des données dans la vue (twig) sous forme de tableau avec indice = >valeur 
        l'indice étant le nom de la variable dans le fichier twig et valeur sa valeur réelle
        */
    
        // ici on a créé une route et une méthode
        #[Route('/blog/ajout', name:"blog_ajout")]
        public function form(Request $globals, EntityManagerInterface $manager) :Response
        {
            $article = new Article();     //on va ratacher cet objet à une entity
            
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
                return $this->redirectToRoute('home'); 

            }


            //ici on va render notre page twig
            return $this->render('blog/form.html.twig', [                 
                'formArticle' => $form
            ]);        
        }


}
