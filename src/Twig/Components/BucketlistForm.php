<?php

namespace App\Twig\Components;

use App\Entity\Bucketlist;
use App\Entity\BucketlistItem;
use App\Form\BucketlistType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class BucketlistForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    /**
     * The initial data used to create the form.
     */
    #[LiveProp]
    public ?Bucketlist $initialFormData = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(BucketlistType::class, $this->initialFormData);
    }

    #[LiveAction]
    public function addCollectionItem(): void
    {
        $bucketlistItem = new BucketlistItem();
        $bucketlistItem->setBucketList($this->initialFormData);

        $this->formValues['bucketlistItems'][] = $bucketlistItem;
    }

    #[LiveAction]
    public function removeCollectionItem(#[LiveArg] int $index): void
    {
        unset($this->formValues['bucketlistItems'][$index]);
    }
}
