<?php

namespace App\Controller;

use App\Dto\User\UserLoginDto;
use App\Form\User\UserLoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route(path: '/login', name: 'user_login')]
    public function login(
        AuthenticationUtils $authenticationUtils
    ): Response {
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
}
