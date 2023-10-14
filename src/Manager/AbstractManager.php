<?php
/**
 * @author Bocasay jul
 * Date : 14/10/2023
 */

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

abstract class AbstractManager
{
    public function __construct(public EntityManagerInterface $entityManager){}

    public function save(object $entity): void
    {
        if (!$entity->getId()) {
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
    }
}