<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Fichier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FichierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, ['label'=>'Nom affiché', 'attr'=>['class'=>'form-control'], 'label_attr' => ['class' => 'form-label'], 'row_attr' => ['class' => 'mb-3']])
            ->add('nomFichier',TextType::class, ['label'=>'Nom du fichier dans public/fichiers', 'attr'=>['class'=>'form-control'], 'label_attr' => ['class' => 'form-label'], 'row_attr' => ['class' => 'mb-3']])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'attr'=>['class'=>'form-select'],
                'label_attr' => ['class' => 'form-label'],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('enregistrer',SubmitType::class, ['attr'=>['class'=>'btn btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fichier::class,
        ]);
    }
}
