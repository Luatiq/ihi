<?php

namespace App\Form;

use App\Entity\User;
use Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $entity = $builder->getData();
        if (!$entity instanceof User) {
            throw new Exception('Invalid entity');
        }

        $builder
            ->add('username', TextType::class, [
                'label' => 'label.username'
            ])
            ->add('password', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'error.passwords_must_match',
                'required' => !$entity->getId(),
                'first_options'  => ['label' => 'label.password'],
                'second_options' => ['label' => 'label.repeat_password'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => $entity->getId() ? 'button.save' : 'button.register',
                'attr' => [
                    'class' => 'btn-primary',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
