<?php

namespace App\Controller;

use App\Contract\Service\UserServiceInterface;
use App\Dto\User\UserLoginDto;
use App\Dto\User\UserRegisterDto;
use App\Form\User\UserRegisterType;
use App\Form\User\UserLoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route(path: '/login', name: 'user_login')]
    public function login(): Response
    {
        $dto = new UserLoginDto();
        $form = $this->createForm(
            UserLoginType::class,
            $dto
        );
        return $this->render(
            'user/login.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    #[Route(path: '/logout', name: 'user_logout')]
    public function logout(): void
    {
    }

    #[Route(path: '/register', name: 'user_register')]
    public function register(
        Request $request,
        UserServiceInterface $userService
    ): Response|RedirectResponse {
        $dto = new UserRegisterDto();
        $form = $this->createForm(
            UserRegisterType::class,
            $dto
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userService->createNewUser($dto);
            $this->addFlash(
                'success',
                'user.register.success'
            );
            return $this->redirectToRoute('homepage');
        }
        return $this->render(
            'user/register.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}
