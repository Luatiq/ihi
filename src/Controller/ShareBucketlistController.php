<?php

namespace App\Controller;

use App\Entity\Bucketlist;
use App\Entity\ShareBucketlist;
use App\Form\ShareBucketlistType;
use App\Repository\BucketlistRepository;
use App\Repository\ShareBucketlistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bucketlist-share', name: 'bucketlist.share')]
class ShareBucketlistController extends AbstractController
{
    private ShareBucketlistRepository $repository;
    private BucketlistRepository $bucketlistRepository;

    public function __construct(
        ShareBucketlistRepository $repository,
        BucketlistRepository $bucketlistRepository
    ) {
        $this->repository = $repository;
        $this->bucketlistRepository = $bucketlistRepository;
    }

    #[Route('/edit/{bucketlist}/{entity}', name: '.edit')]
//    #[Route('/edit', name: '.edit')]
//    #[Route('/edit/{entity}')]
    public function edit(
        Request $request,
        ?Bucketlist $bucketlist = null,
        ?ShareBucketlist $entity = null
    ): Response {
        if (!$bucketlist && !$entity) {
            throw new Exception('no sharebucketlist or bucketlist specified');
        }

        if (!$entity) {
            $entity = new ShareBucketlist();
            $entity->setBucketlist($bucketlist);
        }
        
        $form = $this->createForm(ShareBucketlistType::class, $entity);

        // @TODO does this work with ajax POST?
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->save($entity, true);
        }

        return $this->render('share_bucketlist/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{bucketlist}/create-link', name: '.create_link')]
    public function createLink(
        Bucketlist $bucketlist
    ): JsonResponse {
        $entity = $bucketlist->getShareBucketlistNotNull();
        $entity->setUuid();

        $this->repository->save($entity, true);

        return new JsonResponse([
            'link' => 'linkhere'
        ]);
    }
}
