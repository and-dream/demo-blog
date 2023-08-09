<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        //ce formulaire il faudra l'afficher dans la page blogshow qui prend un id en paramètre ->controller
            ->add('content') // il y aura juste un textarea
            // ->add('created_at')
            // ->add('article')
            // ->add('user')  il sera en session on n'a pas besoin de ça

            //attention tout ça ne peut pas être nullable ce sera à moi de le remplir (et non l'utilisateur)
            // on va set dans le controleur
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
