<?php

namespace App\Twig\Components;

use App\Entity\ShareBucketlist;
use App\Form\ShareBucketlistType;
use App\Repository\BucketlistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ShareBucketlistForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?ShareBucketlist $initialFormData = null;

    #[LiveProp(writable: true, url: true)]
    public int $bucketlistId = 0;

    protected function instantiateForm(): FormInterface {
        return $this->createForm(ShareBucketlistType::class, $this->initialFormData);
    }

    public function __construct(private BucketlistRepository $bucketlistRepository)
    {
    }

    #[LiveAction]
    public function save(EntityManagerInterface $em): void {
        $this->submitForm();

        $entity = $this->getForm()->getData();
        $em->persist($entity);
        $em->flush();
    }

    #[LiveAction]
    public function createLink(EntityManagerInterface $em, Request $request): void {
        $entity = $this->getForm()->getData();
        if ($entity === null) {
            $entity = new ShareBucketlist();
            $bucketlist = $this->bucketlistRepository->find($this->bucketlistId);

            $entity->setBucketlist($bucketlist);
        }

        if (!$entity instanceof ShareBucketlist) {
            throw new Exception('Entity is invalid');
        }

        $entity->setUuid();

        $em->persist($entity);
        $em->flush();

        $this->formValues['uuid'] = $entity->getUuid();
    }
}
