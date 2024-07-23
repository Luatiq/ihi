<?php

namespace App\Form;

use App\Entity\ShareBucketlist;
use Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShareBucketlistType extends AbstractType
{
    /**
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $entity = $builder->getData();
        if (!$entity instanceof ShareBucketlist) {
            throw new Exception('Invalid entity');
        }

        // @TODO add/improve form
        $builder
            ->add('users')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ShareBucketlist::class,
        ]);
    }
}
