<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/account', name: 'account')]
class AccountController extends AbstractController
{
    private UserRepository $repository;

    public function __construct(
        UserRepository $repository
    ) {
        $this->repository = $repository;
    }

    #[Route('/register', name: '.register')]
    // @TODO move to security voter
    #[IsGranted(new Expression('not is_authenticated()'))]
    public function register(
        UserPasswordHasherInterface $passwordHasher,
        Request                     $request
    ): Response {
        $entity = new User();
        $form = $this->createForm(UserType::class, $entity);

        $form->handleRequest($request);

        if (
            $this->repository->findOneBy([
                'username' => $entity->getUsername()
            ])
        ) {
            $form->addError(new FormError('Username has already been taken'));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $request->get('user')['password']['first'];
            $hashedPass = $passwordHasher->hashPassword($entity, $plainPassword);
            $entity->setPassword($hashedPass);

            $this->repository->save($entity, true);

            return $this->redirectToRoute('account.login');
        }

        return $this->render('account/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/login', name: '.login')]
    // @TODO move to security voter
    #[IsGranted(new Expression('not is_authenticated()'))]
    public function login(
        AuthenticationUtils $authenticationUtils
    ): Response {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the account
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'lastUsername' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: '.logout')]
    public function logout(): RedirectResponse {
        // Is never called, security.yaml overrides
        return $this->redirectToRoute('account.login');
    }

    #[Route('/', name: '.view')]
    #[IsGranted('ROLE_USER')]
    public function index(
        UserPasswordHasherInterface $passwordHasher,
        Request $request
    ): Response {
        $entity = $this->getUser();
        if (!$entity instanceof User) {
            throw new Error('Invalid entity');
        }

        $oldEntity = clone $entity;
        $form = $this->createForm(UserType::class, $entity);

        $form->handleRequest($request);
        if (
            $oldEntity->getUsername() !== $entity->getUsername()
            // username has been changed, let's check for dupes
            && $this->repository->findOneBy([
                'username' => $entity->getUsername()
            ])
        ) {
            $form->addError(new FormError('Username has already been taken'));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $request->get('user')['password']['first'];
            if ($plainPassword) {
                $hashedPass = $passwordHasher->hashPassword($entity, $plainPassword);
                $entity->setPassword($hashedPass);
            }

            $this->repository->save($entity, true);
        }

        return $this->render('account/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
