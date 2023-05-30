<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Intitulé de la catégorie',
                'attr' => ['class' => 'input', 'minLenght'=> '2', 'maxLenght'=>'50'],
                'constraints' => [new Assert\Length(['minLenght' => 2, 'maxLenght' => 50]),
                               new Assert\NotBlank()],
            ])
            ->add('envoyer', SubmitType::class, [
                'attr' => ['class' => 'btn btn-submit']
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
