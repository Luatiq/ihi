<?php

namespace App\Form;

use App\Entity\ShareBucketlist;
use Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        if ($entity === null) {
            $entity = new ShareBucketlist();
        }

        if (!$entity instanceof ShareBucketlist) {
            throw new Exception('Invalid entity');
        }

        // @TODO ability to add/remove users
        $builder
            ->add('share-link', TextType::class, [
                'mapped' => false,
                'disabled' => true,
                // @TODO make link :O
                'data' => '$entity->getShareLink()'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ShareBucketlist::class,
        ]);
    }
}
