<?php

namespace App\Form;


use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class StagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'input',
                'minlength'=> '2',
                'maxlength'=> '50'],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ],
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'input', 
                'minlength'=> '2',
                'maxlength'=> '50'],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('sexe', ChoiceType::class, [
                'choices'  => [
                    'Homme' => 'M',
                    'Femme' => 'F'
                ],
                'expanded' => true,
                'attr' => ['class' => 'radio']
            ])
            ->add('dateNaissance', DateType::class, [
                'widget' => 'choice',
                'widget' => 'single_text',
                'attr' => ['class' => 'input'],
                'constraints' => new Assert\NotBlank()
            ])
            ->add('ville', TextType::class,[
                'attr' => ['class' => 'input'],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('email', EmailType::class,[
                'attr' => ['class' => 'input'],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 100]),
                    new Assert\NotBlank()
                ]
            ] )
            ->add('tel', TelType::class, [
                'attr' => ['class' => 'input',
                'minlength'=> '10',
                'maxlength'=> '13'],
                'constraints' => [
                    new Assert\Length(['min' => 10, 'max' => 13]),
                    new Assert\NotBlank()
                ]
            ])
            // ->add('sessions')
            ->add('Envoyer', SubmitType::class, [
                'attr' => ['class' => 'btn btn-submit']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stagiaire::class,
        ]);
    }
}
