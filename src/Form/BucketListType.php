<?php

namespace App\Form;

use App\Entity\BucketList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;







class BucketListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $user = $options['user'];
    $builder
        ->add('title', TextType::class, [
            'label' => "Title",
            'attr' => [
                'placeholder' => 'Enter the title here',
                'class' => 'form-control',
            ],
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'attr' => [
                'class' => 'description-txt',
                'placeholder' => 'Write a brief description...',
            ],
        ])
        ->add('author', TextType::class, [
            'label' => 'Author',
            'attr' => [
                'placeholder' => 'Author name',
                'class' => 'form-control',
            ],
            'data' => $user ? $user->getUsername() : '',  // Pré-remplir avec le nom d'utilisateur connecté
        ])
        
        ->add('category', EntityType::class, [ // Nouveau champ pour la catégorie
            'class' => Category::class, // Entité associée
            'choice_label' => 'name', // Champ à afficher dans la liste
            'label' => 'Category',
            'placeholder' => 'Select a category', // Option vide par défaut
            'required' => true,
            'attr' => [
                'class' => 'form-control', // Ajoute une classe CSS au champ
            ],
        ])
        
        ->add('isPublished', CheckboxType::class, [
            'label' => 'Published',
            'required' => false,
        ])
        ->add('dateCreated', DateType::class, [
            'label' => 'Date Created',
            'widget' => 'single_text',
            'attr' => [
                'class' => 'date-picker',
            ],
        ]);
}


public function configureOptions(OptionsResolver $resolver)
{
    $resolver->setDefaults([
        'data_class' => BucketList::class,
        'user' => null,  // Déclare l'option 'user' avec une valeur par défaut null
    ]);
}
}
