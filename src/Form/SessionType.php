<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Formateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intituleSession', TextType::class,[
            'label' => 'Intitulé de la session',
            'attr' => ['class' => 'input',
            'minlength' =>'2', 'maxlength' => '100']])
            ->add('dateDebut',DateType::class, [
                'label' => 'Date de début',
                'widget' => 'choice',
                'widget' => 'single_text',
                'attr' => ['class' => 'input']
            ])
            ->add('dateFin',DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'choice',
                'widget' => 'single_text',
                'attr' => ['class' => 'input']
            ])
            ->add('nbPlaces',IntegerType::class, [
                'label' => 'Nombre de places',
                'attr' => ['class' => 'input'],

            ])
            ->add('formateur', EntityType::class,[
                'class'=> Formateur::class
            ])
            ->add('Envoyer',SubmitType::class, [
                'attr' => ['class' => 'btn btn-submit']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
