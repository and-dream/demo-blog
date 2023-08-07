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

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

        //une méthode peut avoir 2 routes comme ici
        #[Route('/article/modifier/{id}' , name:"blog_modifier")]

        /*dans le render en 2ème argument on peut envoyer des données dans la vue (twig) sous forme de tableau avec indice = >valeur 
        l'indice étant le nom de la variable dans le fichier twig et valeur sa valeur réelle
        */
    
        // ici on a créé une route et une méthode
        #[Route('/article/ajout', name:"blog_ajout")]
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

                // $imageFile = $form->get('image')->getData();

                // if($imageFile){
                //     $originalFilename = pathinfo($imageFile->getClientOriginalNale(),PATHINFO_FILENAME);
                //     $safeFilename = $slugger->slug($originalFilename);
                //     $newFilename = $safeFilename.'-'.uniquid().'.'.$imageFile->guessExtension();

                //     try{
                //         $imageFile->move(
                //             $this->getParameter('images_directory'),
                //             $newFilename
                //         );
                //     }catch (FileException $e){

                //     }

                //     $article->setImageFilename($filename);
                // }
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
            return $this->render('admin/article/form.html.twig', [                 
                'formArticle' => $form,
                'editMode' => $article->getId() !== null   
                //si je suis en modification je vais avoir un chiffre, si je suis en ajout je vais avoir Null
                // si je suis en ajout ça sera différent
            ]);        
        }

        #[Route('/article/gestion', name:'blog_gestion')]
        public function gestion(ArticleRepository $repo)   //récupérer les articles
        {
            $articles = $repo->findAll();
            return $this->render('admin/article/gestion.html.twig', [    //afficher les articles sur la page
                'articles' => $articles,
            ]);
        }

                //nouvelle route pour la suppression
        //il va falloir savoir quel article supprimer donc on aura besoin de l'id
        //le mananger nous permet de faire un ajout, une modification ou une suppression dans la bdd

        #[Route('/article/supprimer/{id}', name: 'blog_supprimer')]
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
