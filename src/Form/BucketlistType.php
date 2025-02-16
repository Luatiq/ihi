<?php

namespace App\Form;

use App\Entity\Bucketlist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

class BucketlistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('display')
            ->add('description')
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-primary',
                ],
            ])
            ->add('bucketlistItems', LiveCollectionType::class, [
                'entry_type' => BucketlistItemType::class,
            ])
        ;
    }

    // @TODO add BucketlistItem type

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bucketlist::class,
        ]);
    }
}
