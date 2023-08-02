<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=1; $i <= 15; $i++)
        {
            //on va envoyer des données dans la bdd
            
            $article = new Article;

            // on set chacune des propriétés avant de les envoyer en bdd
            //comme elle se retourne elle-même (return->this) on va chaîner donc pas de ';'
            $article->setTitle("Titre de l'article n°$i")
                    ->setImage("https://picsum.photos/200/300")
                    ->setContent("<p>Contenu de l'article n°$i</p>")
                    ->setCreatedAt(new \Datetime());

            //a chaque tour de boucle on va persist notre article
            $manager->persist($article);
            
        }
        // $product = new Product();
        // $manager->persist($product);

        //execution
            $manager->flush();
    }
}
