<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VoitureController extends AbstractController
{
    #[Route('/voiture', name: 'app_voiture')]
    public function index(VoitureRepository $repo): Response
    {
      $voitures = $repo->findAll();  
      return $this->render('voiture/index.html.twig', [
            'items' =>$voitures,
        ]);
    }

    //il ne faut jamais 2 noms de routes similaires
    #[Route('/voiture/ajout', name: 'voiture_ajout')]
    public function ajout(Request $request, EntityManagerInterface $manager, Voiture $voiture = null) 
    {

      $voiture = new Voiture; //l'objet est vide, ses propriétés sont nulles, il a la structure de sa classe


      // * je créé une variable dans laquelle je stocke mon formulaire créé grâce à createForm() et à son formBuilder (VoitureType)
      $form = $this->createForm(VoitureType::class, $voiture); 
      //! à partir d'ici on peut afficher un formulaire sans qu'il soit fonctionnel
      //createForm() va créer un formulaire à partir du builder et on va le stocker dans la variable $form que l'on a créé
      //envoyer le formulaire dans la page twig, on va créé une nouvelle variable formVoiture qui aura pour valeur le formulaire que j'ai créé précédemment dans le controller


      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        //* persist() sert à préparer les requêtes SQL par rapport à l'objet qu'on lui donne en paramètre
        $manager->persist($voiture);
        $manager->flush();
        return $this->redirectToRoute('app_voiture');
      }

      // le 2ème paramètre : tableau de données où on pourra créer autant de variable qu'on veut
      return $this->render('voiture/ajout.html.twig', [
        'formVoiture' => $form
      ]);
    }

    }
//maintenant il faut rendre le formulaire fonctionnel
//il faut récupérer les données dans une superglobale
// *on les passe en paramètre de la fonction ajout()
// on a besoin de la classe Request pour récupérer les globales notamment get et post
// on a besoin de l'entitymanagerinterface pour les requêtes
// !public function ajout(Request $request, EntityManagerInterface $manager) 
//pour remplir chacune de mes tables je vais remplir un objet
//* -> on va devoir instancier l'objet $voiture = new Voiture;
//$form = $this->createForm(VoitureType::class);
//*ratacher mon objet dans le form
//! $form = $this->createForm(VoitureType::class, $voiture);




