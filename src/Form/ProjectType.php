<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices=[

            'in progress'=>'in progress',

            'done'=>'done',
            'blocked'=>'blocked'

        ];
        $builder


            ->add('title',TextareaType::class, [
                'required' => true,
            ])
            ->add('description',TextareaType::class, [

                'required' => true,
            ])

            ->add('status', ChoiceType::class, [
                    'choices'=>$choices,

                    'label'=>' ',

                    'attr'=>[]
                ]


            )
            ->add('number_task',NumberType::class, [

                'required' => true,
            ])
            ->add('picture', FileType::class, [
               'empty_data' => '',
                'data_class'=> null,
                'required' => false,

                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Download a valid Picture ',
                    ])
                ],
            ])
            ->add('filename', FileType::class, [
               'empty_data' => '',
                'data_class'=> null,
                'required' => false,

                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'application/*',
                        ],
                        'mimeTypesMessage' => 'Download a valid file ',
                    ])
                ],
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_preject';
    }
}
