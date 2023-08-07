<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')   //attribut name
            ->add('content')
            ->add('image')
            // , FileType::class, [
            //     'label' => 'Image (image jpg)',
            //     'mapped' => false,
            //     'required' => false,

            //     'constraints' => [
            //         new File ([
            //             'maxSize' => '1024k',
            //             'mimeTypes' => [
            //                 'image/jpg',
            //                 'image/jpeg',
            //             ],
            //             'mimeTypesMessage' => 'Veuillez télécharger une image au format jpg ou jpeg',
            //         ])
            //     ]
            // ]) 

            ->add('category', EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'title'
            ])
            // ->add('created_at')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
