<?php

namespace App\Controller;

use App\Dto\Pager\PagerDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    public static function hydratePagerDto(Request $request): PagerDto
    {
        return new PagerDto(
            (int)$request->query->get('page', '1'),
            (int)$request->query->get('itemsPerPage', '10')
        );
    }

    public static function hydrateFilterDto(Request $request, PagerDto $dto): void
    {
        $dto->setCurrentPage((int)$request->query->get('page', '1'))
            ->setItemsPerPage((int)$request->query->get('itemsPerPage', '10'));
    }
}
