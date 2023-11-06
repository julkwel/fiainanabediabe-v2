<?php
/**
 * @author Bocasay jul
 * Date : 06/11/2023
 */

namespace App\Manager;

use App\Entity\Boky;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BokyManager extends AbstractManager
{
    /**
     * @param FormInterface $form
     * @param Boky          $boky
     *
     * @return bool
     */
    public function createBoky(FormInterface $form, Boky $boky): bool
    {
        /** @var UploadedFile $imageData */
        $imageData = $form->get('couverture')->getData();
        if (!$imageData) {
            $this->save($boky);

            return true;
        }

        $newFilename = $this->uploadFile($this->parameterBag->get('image_dir'), $imageData);
        if ($newFilename) {
            $boky->setCouverture($newFilename);
            $this->save($boky);

            return true;
        }

        return false;
    }
}