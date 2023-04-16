<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseController extends AbstractController
{
    public function __construct(
        protected readonly TranslatorInterface $translator
    ) {
    }

    public function addFlashWithTranslation(string $type, string $translationKey, array $translationParams = []): void
    {
        $message = $this->translator->trans($translationKey, $translationParams);
        $this->addFlash($type, $message);
    }

    public function addSuccessMessage(string $translationKey, array $translationParams = []): void
    {
        $this->addFlashWithTranslation('success', $translationKey, $translationParams);
    }

    public function addErrorMessage(string $translationKey, array $translationParams = []): void
    {
        $this->addFlashWithTranslation('danger', $translationKey, $translationParams);
    }
}
