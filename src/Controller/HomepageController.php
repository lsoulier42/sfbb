<?php

namespace App\Controller;

use App\Contract\Service\CategoryServiceInterface;
use App\Contract\Service\HomepageServiceInterface;
use App\Dto\User\UserLoginDto;
use App\Form\User\UserLoginType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends BaseController
{
    #[Route(path: '/', name: 'homepage')]
    public function index(
        CategoryServiceInterface $categoryService,
        HomepageServiceInterface $homepageService
    ): Response {
        $dto = new UserLoginDto();
        $form = $this->createForm(
            UserLoginType::class,
            $dto,
            [
                'action' => $this->generateUrl('user_login')
            ]
        );
        $categoriesVms = $categoryService->getCategoryViewModels();
        $whoIsOnlineVm = $homepageService->getWhoIsOnlineVm();
        return $this->render(
            'homepage/index.html.twig',
            [
                'categories' => $categoriesVms,
                'form' => $form->createView(),
                'who_is_online_vm' => $whoIsOnlineVm
            ]
        );
    }
}
