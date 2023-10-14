<?php
/**
 * @author Bocasay jul
 * Date : 14/10/2023
 */

namespace App\Manager;

use App\Entity\Slide;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class SliderManager extends AbstractManager
{
    public function createSlide(Slide $slide, FormInterface $form)
    {
        /** @var UploadedFile $imageData */
        $imageData = $form->get('image')->getData();
        if (!$imageData) return false;

        $newFilename = $this->uploadFile($this->parameterBag->get('image_dir'), $imageData);
        if ($newFilename) {
            $slide->setImage($newFilename);
            $this->save($slide);

            return true;
        }

        return false;
    }

    public function removeSlide(Slide $slide): void
    {
        $this->entityManager->remove($slide);
        $this->entityManager->flush();
    }
}