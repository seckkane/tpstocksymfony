<?php

namespace App\Form;
use App\Entity\Entree;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntreeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateE', DateType::class, 
                             ['label' => 'Date Achat Produit' , 
                            'attr' => array('class' => 'form-control form-group') ])
            ->add('qtE', TextType::class, 
                             ['label' => 'Quantité acheté' , 
                            'attr' => array('class' => 'form-control form-group') ])
            ->add('produit', EntityType::class, 
                             ['label' => 'Produit' , 
                             'class' => Produit::class,
                            'attr' => array('class' => 'form-control form-group') ])
            ->add('valider', SubmitType::class,
                             ['attr' => array('class' => 'btn btn-warning form-group')  ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entree::class,
        ]);
    }
}
