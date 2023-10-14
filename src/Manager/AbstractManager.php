<?php
/**
 * @author Bocasay jul
 * Date : 14/10/2023
 */

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

abstract class AbstractManager
{
    public function __construct(public EntityManagerInterface $entityManager, public ParameterBagInterface $parameterBag, public SluggerInterface $slugger){}

    public function save(object $entity): void
    {
        if (!$entity->getId()) {
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
    }

    public function uploadFile(string $dir, UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($dir, $newFilename);

            return $newFilename;
        } catch (FileException $e) {
            throw new FileException($e->getMessage());
        }
    }
}