<?php

namespace App\Form;

use App\Entity\SampleProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class SampleProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,
                [
                    'attr' => [ 'class' => 'form-control' ],
                ]
            )
            ->add('position', IntegerType::class,
                [
                    'attr' => ['class' => 'form-control' ],
                ]
            )
            // ->add('image', FileType::class, 
            //     [
            //         'mapped' => false,
            //         'required' => false,
            //     ]
            // )
            // ->add('image', AddressType::class, 
            //     [
            //         'label_format' => 'form.address.%name%',
            //         'mapped' => false,
            //         'required' => false,
            //     ]
            // )
            // ->add('image', UrlType::class,
            //     [
            //         'attr' => [ 'class' => 'form-control' ],
            //         'required' => false,
            //     ]
            // )
            ->add('image', TextType::class,
                [
                    'attr' => [ 'class' => 'form-control' ],
                    'required' => false,
                ]
            )
            ->add('how_many', IntegerType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'style' => 'display:none;',
                        ],
                    'data' => '0',
                    'label' => false,
                ]
            )
            ->add('sample_items', CollectionType::class, [
                'entry_type' => SampleItemType::class,
                'entry_options' => ['label' => false],
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
            ])
            ->add('save', SubmitType::class, 
                [
                    'attr' => [ 'class' => 'btn btn-primary float-right' ]
                ]
            )


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SampleProduct::class,
        ]);
    }
}
