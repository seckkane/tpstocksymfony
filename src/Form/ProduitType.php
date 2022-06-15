<?php

namespace App\Form;
use App\Entity\Produit;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class, 
                             ['label' => 'Libelle du Produit' , 
                            'attr' => array('class' => 'form-control form-group') ] )
            ->add('qtStock', TextType::class, 
                             ['label' => 'Quantité en Stock' , 
                            'attr' => array('class' => 'form-control form-group')  ])
            ->add('categorie', EntityType::class, 
                             ['label' => 'Categorie' , 
                             'class' => Categorie::class,
                            'attr' => array('class' => 'form-control form-group') ])
            ->add('valider', SubmitType::class,
                             ['attr' => array('class' => 'btn btn-warning form-group') ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
