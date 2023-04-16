<?php

namespace App\Controller;

use App\Contract\Service\CategoryServiceInterface;
use App\Dto\User\UserLoginDto;
use App\Form\User\UserLoginType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends BaseController
{
    #[Route(path: '/', name: 'homepage')]
    public function index(CategoryServiceInterface $categoryService): Response
    {
        $dto = new UserLoginDto();
        $form = $this->createForm(
            UserLoginType::class,
            $dto,
            [
                'action' => $this->generateUrl('user_login')
            ]
        );
        $categories = $categoryService->listAll();
        return $this->render(
            'homepage/index.html.twig',
            [
                'categories' => $categories,
                'form' => $form->createView()
            ]
        );
    }
}
