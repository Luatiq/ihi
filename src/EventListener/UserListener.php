<?php

namespace App\EventListener;

use App\Entity\User;

class UserListener
{
    public function prePersist(User $entity): void {
        $entity->setName();
}
}
