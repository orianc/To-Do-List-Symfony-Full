<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Todo;
use DateTime;
use PhpParser\Node\Stmt\Label;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TodoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'Titre',
                    'attr' => [
                        'placeholder' => 'Entrez le titre ici'
                    ]
                ]
            )
            ->add(
                'content',
                TextareaType::class,
                [
                    'label' => 'Description',
                    'attr' => ['placeholder' => "Décrivez la tache ici"]
                ]
            );
        if ($options['data']->getId() == null) {

            $builder->add('date_for', DateType::class, [
                'label' => 'A faire pour :',
                'years' => ['2021', '2022'],
                'format' => 'dd MM yyyy',
                'data' => new DateTime()
            ]);
        } else {
            $builder->add('date_for', DateType::class, [
                'label' => 'A faire pour :',
                'years' => ['2021', '2022'],
                'format' => 'dd MM yyyy',
            ]);
        }

        $builder->add('category', EntityType::class, [
            'label' => 'Catégorie',
            'class' => Category::class,
            'choice_label' => 'Name'
        ])
            ->add('valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Todo::class,
        ]);
    }
}
