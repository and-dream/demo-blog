<?php

namespace App\DataFixtures;


use Faker\Factory;
use Faker\Generator;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
     /**
     *  @var Generator
     */
    private Generator $faker;

    public function __construct()
    { 
        $this->faker = Factory::create('fr_FR');
    }


    public function load(ObjectManager $manager): void
    {
        
        for($j =1; $j <= 3; $j++)
        {
            $category = new Category;
            $category->setTitle($this->faker->sentence(1))
                    ->setDescription($this->faker->paragraph(3));
            $manager->persist($category);

            for($i=1; $i <= mt_rand(8, 12); $i++)
            {
                //on va envoyer des données dans la bdd
                
                $article = new Article;
    
                // on set chacune des propriétés avant de les envoyer en bdd
                //comme elle se retourne elle-même (return->this) on va chaîner donc pas de ';'
                $article->setTitle($this->faker->sentence())
                        ->setImage($this->faker->imageUrl())
                        ->setContent($this->faker->paragraph(200))
                        ->setCreatedAt($this->faker->dateTimeBetween('-10 months'))
                        ->setCategory($category);
                       
    
                //a chaque tour de boucle on va persist notre article
                $manager->persist($article);

                for($k =1; $k <= mt_rand(4, 10); $k++)
                {
                    $comment = new Comment;

                    //on récupère la date d'aujourd'hui
                    $now = new \DateTime();
                    // on fait la différence entre la date d'aujourdh'ui et la date de création de notre article
                    //combien de jours se sont écoulés entre la date de création de l'article et la date du com
                    $interval = $now->diff($article->getCreatedAt());
                    //on récupère la différence en nombre de jours
                    // on connait le nb de jours d'écart
                    $days = $interval->days;
                    $minimum = '-' . $days . ' days';

                    // $comment->setAuthor($this->faker->name)
                    $comment->setContent($this->faker->paragraph())
                            ->setArticle($article)
                            ->setCreatedAt($this->faker->dateTimeBetween($minimum));
                    //une fois qu'on a set on fait notre persist, préparer la requête pour insérer (pour l'instant on ne l'envoie pas)
                    $manager->persist($comment);
                }
                
            }
            // $product = new Product();
            // $manager->persist($product);
    
            //execution
                $manager->flush();
        } 
        }


     
}
