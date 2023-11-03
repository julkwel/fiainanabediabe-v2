<?php
/**
 * @author Bocasay jul
 * Date : 15/10/2023
 */

namespace App\Manager;

use App\Entity\Post;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PostManager extends AbstractManager
{
    public function createPost(FormInterface $form, Post $post): bool
    {
        /** @var UploadedFile $imageData */
        $imageData = $form->get('couverture')->getData();
        if (!$imageData) {
            $this->save($post);

            return true;
        }

        $newFilename = $this->uploadFile($this->parameterBag->get('image_dir'), $imageData);
        if ($newFilename) {
            $post->setCouverture($newFilename);
            $this->save($post);

            return true;
        }

        return false;
    }

    public function removePost(Post $post)
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

}