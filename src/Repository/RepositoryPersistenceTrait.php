<?php

namespace App\Repository;

trait RepositoryPersistenceTrait
{
    public function persist(mixed $entity): void
    {
        $this->getEntityManager()->persist($entity);
    }

    public function save(): void
    {
        $this->getEntityManager()->flush();
    }

    public function remove(mixed $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}

