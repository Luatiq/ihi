<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
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
    public function register(
        ?User                       $entity,
        UserPasswordHasherInterface $passwordHasher,
        Request                     $request
    ): Response {
        // @TODO move to event listener?
        if ($this->getUser()) {
            return $this->redirectToRoute('account.view');
        }

        if (!$entity) $entity = new User();

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
            $hashedPass = $passwordHasher->hashPassword($entity, $entity->getPassword());
            $entity->setPassword($hashedPass);

            $this->repository->save($entity, true);

            return $this->redirectToRoute('account.login');
        }

        return $this->render('account/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/login', name: '.login')]
    public function login(
        AuthenticationUtils $authenticationUtils
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('account.view');
        }

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

    #[Route('/', name: '.view', methods: ['GET'])]
    public function index(): Response {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
}
