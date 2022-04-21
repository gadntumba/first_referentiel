<?php

namespace App\Form;

use App\Entity\Productor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('firstName')
            ->add('lastName')
            ->add('sexe')
            ->add('phoneNumber')
            ->add('birthdate')
            ->add('nui')
            ->add('latitude')
            ->add('longitude')
            ->add('altitude')
            ->add('numberPieceOfIdentification')
            ->add('photoPieceOfIdentification')
            ->add('householdSize')
            ->add('deletedAt')
            ->add('incumbentPhoto')
            ->add('imageFile', FileType::class,[
                'required'=>false
            ])
            ->add('createdAt')
            ->add('updatedAt')
            ->add('slug')
            ->add('levelStudy')
            ->add('housekeeping')
            ->add('smartphone')
            ->add('monitor')
            ->add('typePieceOfIdentification')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Productor::class,
        ]);
    }
}
