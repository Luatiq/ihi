<?php

namespace App\Form;

use App\Entity\User;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    private Security $security;

    public function __construct(
        Security $security
    ) {
        $this->security = $security;
    }

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
        ;

        if ($this->security->getUser()) {
            $builder
                ->add('aboutMe')
                ->add('name')
            ;
        }

        if (
            !$this->security->getUser()
            || (
                $this->security->getUser()
                && $entity->getId() === $this->security->getUser()->getId()
            )
        ) {
            $builder
                ->add('password', RepeatedType::class, [
                    'mapped' => false,
                    'type' => PasswordType::class,
                    'invalid_message' => 'error.passwords_must_match',
                    'required' => !$entity->getId(),
                    'first_options'  => ['label' => 'label.password'],
                    'second_options' => ['label' => 'label.repeat_password'],
                ])
            ;
        }

        $builder
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
