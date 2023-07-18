<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('user', name: 'user')]
class UserController extends AbstractController
{
    private UserRepository $repository;

    public function __construct(
        UserRepository $repository
    ) {
        $this->repository = $repository;
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/overview', name: '.overview')]
    public function overview(): Response {
        return $this->render('user/overview.html.twig', [
            'entities' => $this->repository->findAll(),
        ]);
    }

    /**
     * @throws LogicException
     */


    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{entity}/edit', name: '.edit')]
    public function edit(
        User $entity,
        Request $request
    ): Response {
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
            $this->repository->save($entity, true);
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form,
        ]);
    }
}