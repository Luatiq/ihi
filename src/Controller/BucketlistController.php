<?php

namespace App\Controller;

use App\Entity\Bucketlist;
use App\Form\BucketlistType;
use App\Repository\BucketlistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/bucket-list', name: 'bucketlist')]
class BucketlistController extends AbstractController
{
    private BucketlistRepository $repository;

    public function __construct(
        BucketlistRepository $repository
    ) {
        $this->repository = $repository;
    }

    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('', name: '.overview')]
    public function overview(): Response {
        if (!$this->getUser()->hasBucketlists()) {
            return $this->redirectToRoute('bucketlist.edit');
        }

        return $this->render('bucketlist/overview.html.twig', [
            'entities' => $this->repository->findBy([
                'user' => $this->getUser()
            ]),
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/edit/{entity}', name: '.edit')]
    public function edit(
        Request $request,
        ?Bucketlist $entity = null
    ): Response {
        $entity = $entity ?? new Bucketlist();
        $form = $this->createForm(BucketlistType::class, $entity);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->save($entity, true);

            return $this->redirectToRoute('bucketlist.overview');
        }

        return $this->render('bucketlist/edit.html.twig', [
            'form' => $form,
        ]);
    }
}